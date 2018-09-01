<?php

use yii\db\Migration;

/**
 * Handles the creation of table `kinds`.
 */
class m180816_093813_create_kinds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('kinds', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'status' => $this->tinyInteger(4)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('kinds');
    }
}
