<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180816_033752_create_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'dates' => $this->integer(20),
            'kind_id' => $this->integer(11)->notNull(),
            'status' => $this->tinyInteger()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article');
    }
}
