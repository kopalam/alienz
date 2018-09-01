<?php

use yii\db\Migration;

/**
 * Handles the creation of table `teacher_course`.
 */
class m180827_030718_create_teacher_course_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('teacher_course', [
            'id' => $this->primaryKey(),
            'teacher_id' => $this->integer(20)->notNull(),
            'course_id' =>$this->integer(20)->notNull(),
            'status' =>$this->tinyInteger(4)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('teacher_course');
    }
}
