<?php

use yii\db\Migration;

/**
 * Handles the creation of table `course_set`.
 */
class m180817_025004_create_course_set_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('course_set', [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(4)->notNull(),
            'course_id' => $this->integer(20)->notNull(),
            'stime' => $this->integer(20)->notNull(),
            'etime' => $this->integer(20)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('course_set');
    }
}
