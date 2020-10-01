<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message}}`.
 */
class m201001_093548_create_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(),
            'text' => $this->text()->notNull(),
            'is_banned' => $this->boolean()->notNull()->defaultValue(0),
            'created_by_id' => $this->integer()->notNull(),
            'updated_by_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-message-created_by_id',
            '{{%message}}',
            'created_by_id'
        );
        $this->addForeignKey(
            'fk-message-created_by_id',
            '{{%message}}',
            'created_by_id',
            '{{%user}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-message-created_by_id',
            '{{%message}}'
        );
        $this->dropIndex(
            'idx-message-created_by_id',
            '{{%message}}'
        );

        $this->dropTable('{{%message}}');
    }
}
