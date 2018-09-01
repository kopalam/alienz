<?php

use yii\db\Migration;

/**
 * Handles the creation of table `course`.
 */
class m180817_020406_create_course_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('course', [
            'id' => $this->primaryKey(),
            'name' => $this->string(500)->notNull(),
            'status' => $this->tinyInteger(4)->notNull(),
            'content' =>$this->text()->notNull(),
            'teacher_id' =>$this->integer(20)->notNull(),
            'cover' => $this->text()->notNull(),
            'start' => $this->tinyInteger(4)->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('course');
    }
}
