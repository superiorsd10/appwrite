<?php

namespace Appwrite\Platform\Tasks;

use Utopia\Platform\Action;
use Utopia\CLI\Console;
use Utopia\Database\DateTime;
use Utopia\Database\Document;
use Utopia\Database\Query;
use Utopia\Database\Database;
use Utopia\Pools\Group;
use Utopia\Database\ID;

use function Swoole\Coroutine\run;

class CreateMissingSchedules extends Action
{

    public static function getName(): string
    {
        return 'create-missing-schedules';
    }

    public function __construct()
    {
        $this
            ->desc('Ensure every function has a schedule')
            ->inject('dbForConsole')
            ->inject('getProjectDB')
            ->callback(fn (Database $dbForConsole, callable $getProjectDB) => $this->action($dbForConsole, $getProjectDB));
    }

    /**
     * Iterate over every function on every project to make sure there is a schedule. If not, recreate the schedule.
     */
    public function action(Database $dbForConsole, callable $getProjectDB): void
    {
        Console::title('CreateMissingSchedules V1');
        Console::success(APP_NAME . ' CreateMissingSchedules v1 has started');

        $limit = 100;
        $projectCursor = null;
        while (true) {
            $projectsQueries = [Query::limit($limit)];
            if ($projectCursor !== null) {
                $projectsQueries[] = Query::cursorAfter($projectCursor);
            }
            $projects = $dbForConsole->find('projects', $projectsQueries);

            if (count($projects) === 0) break;

            foreach ($projects as $project) {
                Console::log("Checking Project " . $project->getAttribute('name') . " (" . $project->getId() . ")");
                $dbForProject = $getProjectDB($project);
                $functionCursor = null;

                while (true) {
                    $functionsQueries = [Query::limit($limit)];
                    if ($functionCursor !== null) {
                        $functionsQueries[] = Query::cursorAfter($functionCursor);
                    }
                    $functions = $dbForProject->find('functions', $functionsQueries);
                    if (count($functions) === 0) break;

                    foreach ($functions as $function) {
                        run(
                            function () use ($function, $dbForConsole, $project) {
                                Console::log("Checking Function " . $function->getAttribute('name') . " (" . $function->getId() . ")");
                                $scheduleId = $function->getAttribute('scheduleId');
                                $schedules = $dbForConsole->find('schedules', [Query::equal('$id', [$scheduleId])]);

                                if (empty($schedules)) {
                                    $functionId = $function->getId();
                                    Console::success('Recreated schedule for function ' . $functionId);
                                    $dbForConsole->createDocument('schedules', new Document([
                                        '$id' => ID::custom($scheduleId),
                                        'region' => $project->getAttribute('region', 'default'),
                                        'resourceType' => 'function',
                                        'resourceId' => $functionId,
                                        'resourceUpdatedAt' => DateTime::now(),
                                        'projectId' => $project->getId(),
                                        'schedule'  => $function->getAttribute('schedule'),
                                        'active' => !empty($function->getAttribute('schedule')) && !empty($function->getAttribute('deployment')),
                                    ]));
                                }
                            }
                        );
                    }

                    $functionCursor = $functions[array_key_last($functions)];
                }
            }

            $projectCursor = $projects[array_key_last($projects)];
        }
    }
}