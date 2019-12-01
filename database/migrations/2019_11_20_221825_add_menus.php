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
        $setup_menu_id = CommonMigrations::addMenu(0, 8, 'Properties Setup', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($setup_menu_id, 9, 'Projects', 'fa-plus', 'projects', 'projects_list');
        CommonMigrations::addMenu($setup_menu_id, 10, 'Phases', 'fa-gear', 'phases', 'phases_list');
        CommonMigrations::addMenu($setup_menu_id, 11, 'Blocks', 'fa-cubes', 'blocks', 'blocks_list');
        CommonMigrations::addMenu($setup_menu_id, 12, 'Property Types', 'fa-list', 'property-types', 'property_types_list');

        $setup_menu_id = CommonMigrations::addMenu(0, 13, 'Properties Management', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($setup_menu_id, 14, 'Properties', 'fa-home', 'properties', 'properties_list');
        CommonMigrations::addMenu($setup_menu_id, 15, 'Bookings', 'fa-book', 'bookings', 'bookings_list');
        CommonMigrations::addMenu($setup_menu_id, 16, 'Booking Cancellations', 'fa-times-rectangle', 'booking-cancellations', 'booking_cancellations_list');
        CommonMigrations::addMenu($setup_menu_id, 17, 'Allotments', 'fa-book', 'allotments', 'allotments_list');
        CommonMigrations::addMenu($setup_menu_id, 18, 'Allotment Cancellations', 'fa-times-rectangle', 'allotment-cancellations', 'allotment_cancellations_list');
        CommonMigrations::addMenu($setup_menu_id, 19, 'Possessions', 'fa-book', 'possessions', 'possessions_list');
        CommonMigrations::addMenu($setup_menu_id, 20, 'Possession Cancellations', 'fa-times-rectangle', 'possession-cancellations', 'possession_cancellations_list');
        CommonMigrations::addMenu($setup_menu_id, 21, 'Transfers', 'fa-book', 'transfers', 'transfers_list');


        $setup_menu_id = CommonMigrations::addMenu(0, 23, 'Accounting Setup', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($setup_menu_id, 24, 'Account Heads', 'fa-cubes', 'accounts', 'account_heads_list');
        CommonMigrations::addMenu($setup_menu_id, 25, 'Persons', 'fa-users', 'people', 'persons_list');

        $setup_menu_id = CommonMigrations::addMenu(0, 26, 'Accounting Management', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($setup_menu_id, 27, 'Journal Vouchers', 'fa-list', 'journal-vouchers', 'journal_vouchers_list');
        CommonMigrations::addMenu($setup_menu_id, 28, 'Payments', 'fa-money', 'payments', 'payments_list');
        CommonMigrations::addMenu($setup_menu_id, 29, 'Receipts', 'fa-money', 'receipts', 'receipts_list');

        $setup_menu_id = CommonMigrations::addMenu(0, 30, 'Accounting Reports', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($setup_menu_id, 31, 'Receivables Report', 'fa-money', 'receivables-report', 'receivables_report');
        CommonMigrations::addMenu($setup_menu_id, 32, 'Payables Report', 'fa-money', 'payables-report', 'payables_report');
        CommonMigrations::addMenu($setup_menu_id, 33, 'Ledger Report', 'fa-balance-scale', 'ledger-report', 'ledger_report');
        CommonMigrations::addMenu($setup_menu_id, 34, 'Trial Balance Report', 'fa-balance-scale', 'trial-balance-report', 'trial_balance_report');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenu(9, 'Projects');
        CommonMigrations::removeMenu(10, 'Phases');
        CommonMigrations::removeMenu(11, 'Blocks');
        CommonMigrations::removeMenu(12, 'Property Types');
    }
}
