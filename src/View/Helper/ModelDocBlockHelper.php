<?php

namespace MyBake\View\Helper;

use Cake\View\Helper;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;

/**
 * @property \Bake\View\Helper\DocBlockHelper $DocBlock
 */
class ModelDocBlockHelper extends Helper
{

    public $helpers = ['Bake.DocBlock'];

    private $table;

    private $connection;

    public function initialize(array $config)
    {
        parent::initialize($config);
    }

    public function implementedEvents()
    {
        $events = parent::implementedEvents();
        $events['Bake.beforeRender.Model.table'] = 'beforeRenderTable';
        return $events;
    }

    public function beforeRenderTable(Event $event)
    {
        $view = $event->getSubject();
        /* @var $view Bake\View\BakeView */
        $this->table = $view->viewVars['table'];
        $this->connection = $view->viewVars['connection'];

        $behaviors = isset($view->viewVars['behaviors']) ? $view->viewVars['behaviors'] : [];
        $view->viewVars['behaviors'] = $this->setTimestampBehavior($behaviors);
    }

    private function setTimestampBehavior($behaviors)
    {
        $fields = array_keys($this->getTableColumns());
        if (in_array('created_at', $fields)) {
            $options[] = "'created_at' => 'new'";
        }
        if (in_array('updated_at', $fields)) {
            $options[] = "'updated_at' => 'always'";
        }
        if (!empty($options)) {
            $sp8 = str_repeat(' ', 8);
            $sp12 = str_repeat(' ', 12);
            $sp16 = str_repeat(' ', 16);
            $sp20 = str_repeat(' ', 20);
            $behaviors['Timestamp'] = [
                sprintf("\n%s'events' => [\n%s'Model.beforeSave' => [\n%s%s\n%s]\n%s]\n%s", $sp12, $sp16, $sp20, implode(",\n" . $sp20, $options), $sp16, $sp12, $sp8),
            ];
        }
        return $behaviors;
    }

    /**
     *
     * @param string $name Entity name
     * @return array
     */
    private function getTableColumns()
    {
        $table = TableRegistry::get(Inflector::classify($this->table), [
                'connection' => ConnectionManager::get($this->connection),
                'table' => $this->table,
        ]);
        $schema = $table->getSchema();
        $columns = [];
        foreach ($schema->columns() as $column) {
            $columns[$column] = $schema->column($column);
        }
        return $columns;
    }

    /**
     * Renders a map of DocBlock property types as an array of
     * `@property` hints.
     *
     * @return array
     */
    public function entityPropertyHints()
    {
        $columns = $this->getTableColumns();
        $lines = [];
        foreach ($columns as $column => $schema) {
            $type = isset($schema['type']) ? $this->DocBlock->columnTypeToHintType($schema['type']) . ' ' : '';
            $comment = isset($schema['comment']) ? ' ' . $schema['comment'] : '';
            $lines[] = "@property {$type}\${$column}{$comment}";
        }

        return $lines;
    }
}
