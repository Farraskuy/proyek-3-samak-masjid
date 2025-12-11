<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::unprepared("
            CREATE OR REPLACE FUNCTION limit_website_information_rows()
            RETURNS trigger AS $$
            BEGIN
                IF (SELECT COUNT(*) FROM website_information) >= 1 THEN
                    RAISE EXCEPTION 'Table website_information hanya boleh berisi 1 row';
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER website_information_limit_trigger
            BEFORE INSERT ON website_information
            FOR EACH ROW
            EXECUTE FUNCTION limit_website_information_rows();
        ");
    }

    public function down()
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS website_information_limit_trigger ON website_information;
            DROP FUNCTION IF EXISTS limit_website_information_rows();
        ");
    }
};
