<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%estimates}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%books}}`
 */
class m221217_130803_create_estimates_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%estimates}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'value' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        // creates index for column `book_id`
        $this->createIndex(
            '{{%idx-estimates-book_id}}',
            '{{%estimates}}',
            'book_id'
        );

        // add foreign key for table `{{%books}}`
        $this->addForeignKey(
            '{{%fk-estimates-book_id}}',
            '{{%estimates}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%books}}`
        $this->dropForeignKey(
            '{{%fk-estimates-book_id}}',
            '{{%estimates}}'
        );

        // drops index for column `book_id`
        $this->dropIndex(
            '{{%idx-estimates-book_id}}',
            '{{%estimates}}'
        );

        $this->dropTable('{{%estimates}}');
    }
}
