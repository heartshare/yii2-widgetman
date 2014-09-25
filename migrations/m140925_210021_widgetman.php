<?php

use yii\db\Schema;
use yii\db\Migration;

class m140925_210021_widgetman extends Migration
{
    public function safeUp()
    {
        $tableOptions = "";
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable(
            '{{%widgetman}}', [
                'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                'name' => 'VARCHAR(100) NOT NULL',
                'class' => 'VARCHAR(200) NOT NULL',
                'options' => 'TEXT NOT NULL',
                'content' => 'TEXT NOT NULL',
                'position' => 'VARCHAR(50) NOT NULL',
                'ord' => 'INT(11) NOT NULL',
                'cachetime' => 'INT(11) NOT NULL DEFAULT \'300\'',
                'active' => 'TINYINT(1) NOT NULL DEFAULT \'1\'',
                'updated' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ',
                'PRIMARY KEY (`id`)'
            ], $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%widgetman}}');

        return false;
    }
}
