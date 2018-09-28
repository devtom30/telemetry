<?php


use Phinx\Migration\AbstractMigration;

class KimiosReference extends AbstractMigration
{
    /**
     * Up method
     *
     * @return void
     */
        public function up()
        {
            $table = $this->table('kimios_reference');
            $table
                ->addColumn('reference_id', 'integer')
                ->addForeignKey(
                    'reference_id',
                    'reference',
                    'id',
                    ['constraint' => 'telemetry_kimios_reference_reference_id_fkey']
                )
                ->addColumn('num_users', 'integer', ['null' => true])
                ->addColumn('num_documents', 'integer', ['null' => true])
                ->addTimestamps()
                ->addIndex(['reference_id'], ['unique' => true])
                ->create()
            ;
        }

        /**
         * Down method
         *
         * @return void
         */
        public function down()
        {
            //no down for data; sorry!

            $this->dropTable('glpi_reference');
        }
    }