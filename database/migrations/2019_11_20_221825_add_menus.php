<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $parent_menu_id = CommonMigrations::addMenu(0, 8, 'Properties Setup', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 9, 'Projects', 'fa-plus', 'projects', 'projects_list');
        CommonMigrations::addMenu($parent_menu_id, 10, 'Phases', 'fa-gear', 'phases', 'phases_list');
        CommonMigrations::addMenu($parent_menu_id, 11, 'Blocks', 'fa-cubes', 'blocks', 'blocks_list');
        CommonMigrations::addMenu($parent_menu_id, 12, 'Property Types', 'fa-list', 'property-types', 'property_types_list');

        $parent_menu_id = CommonMigrations::addMenu(0, 101, 'Properties Management', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 102, 'Files', 'fa-file', 'files', 'files_list');
        CommonMigrations::addMenu($parent_menu_id, 103, 'Dealer Filer Bookings', 'fa-book', 'dealer-file-bookings', 'dealer_file_bookings_list');
        CommonMigrations::addMenu($parent_menu_id, 104, 'Properties', 'fa-home', 'properties', 'properties_list');
        CommonMigrations::addMenu($parent_menu_id, 105, 'Bookings', 'fa-book', 'bookings', 'bookings_list');
        CommonMigrations::addMenu($parent_menu_id, 106, 'Booking Cancellations', 'fa-times-rectangle', 'booking-cancellations', 'booking_cancellations_list');
        CommonMigrations::addMenu($parent_menu_id, 107, 'Allotments', 'fa-book', 'allotments', 'allotments_list');
        CommonMigrations::addMenu($parent_menu_id, 108, 'Allotment Cancellations', 'fa-times-rectangle', 'allotment-cancellations', 'allotment_cancellations_list');
        CommonMigrations::addMenu($parent_menu_id, 109, 'Possessions', 'fa-book', 'possessions', 'possessions_list');
        CommonMigrations::addMenu($parent_menu_id, 110, 'Possession Cancellations', 'fa-times-rectangle', 'possession-cancellations', 'possession_cancellations_list');
        CommonMigrations::addMenu($parent_menu_id, 111, 'Transfers', 'fa-book', 'transfers', 'transfers_list');

        $parent_menu_id = CommonMigrations::addMenu(0, 201, 'Accounting Setup', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 202, 'Account Heads', 'fa-cubes', 'account-heads', 'account_heads_list');
        CommonMigrations::addMenu($parent_menu_id, 203, 'Persons', 'fa-users', 'people', 'persons_list');

        $parent_menu_id = CommonMigrations::addMenu(0, 301, 'Accounting Management', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 302, 'Journal Vouchers', 'fa-list', 'journal-vouchers', 'journal_vouchers_list');
        CommonMigrations::addMenu($parent_menu_id, 303, 'Payments', 'fa-money', 'payments', 'payments_list');
        CommonMigrations::addMenu($parent_menu_id, 304, 'Receipts', 'fa-money', 'receipts', 'receipts_list');

        $parent_menu_id = CommonMigrations::addMenu(0, 401, 'Accounting Reports', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 402, 'Receivables Report', 'fa-money', 'receivables-report', 'receivables_report');
        CommonMigrations::addMenu($parent_menu_id, 403, 'Payables Report', 'fa-money', 'payables-report', 'payables_report');
        CommonMigrations::addMenu($parent_menu_id, 404, 'Ledger Report', 'fa-balance-scale', 'ledger-report', 'ledger_report');
        CommonMigrations::addMenu($parent_menu_id, 405, 'Trial Balance Report', 'fa-balance-scale', 'trial-balance-report', 'trial_balance_report');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenuByOrderRange(8, 405);
        
    }
}
