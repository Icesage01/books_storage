<?php

use yii\db\Migration;

class m250901_022512_add_is_active_to_author extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%author}}', 'isActive', $this->boolean()->defaultValue(true)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%author}}', 'isActive');
    }
}
