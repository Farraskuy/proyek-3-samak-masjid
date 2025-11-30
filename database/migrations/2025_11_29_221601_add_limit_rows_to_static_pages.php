<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared("
            CREATE OR REPLACE FUNCTION limit_static_pages_rows()
            RETURNS trigger AS $$
            BEGIN
                IF (SELECT COUNT(*) FROM static_pages) >= 1 THEN
                    RAISE EXCEPTION 'Table static_pages hanya boleh berisi 1 row';
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER static_pages_limit_trigger
            BEFORE INSERT ON static_pages
            FOR EACH ROW
            EXECUTE FUNCTION limit_static_pages_rows();
        ");
    }

    public function down()
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS static_pages_limit_trigger ON static_pages;
            DROP FUNCTION IF EXISTS limit_static_pages_rows();
        ");
    }
};
