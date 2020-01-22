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
        CommonMigrations::addMenu($parent_menu_id, 102, 'Land Purchases', 'fa-file', 'land-purchases', 'land_purchases_list');
        CommonMigrations::addMenu($parent_menu_id, 103, 'Property Files', 'fa-file', 'property-files', 'property_files_list');
        CommonMigrations::addMenu($parent_menu_id, 104, 'Dealer Bookings', 'fa-book', 'dealer-bookings', 'dealer_bookings_list');
        CommonMigrations::addMenu($parent_menu_id, 105, 'Dealer Booking Returns', 'fa-book', 'dealer-booking-returns', 'dealer_booking_returns_list');
        CommonMigrations::addMenu($parent_menu_id, 106, 'Bookings', 'fa-book', 'bookings', 'bookings_list');
        CommonMigrations::addMenu($parent_menu_id, 107, 'Booking Cancellations', 'fa-times-rectangle', 'booking-cancellations', 'booking_cancellations_list');
        CommonMigrations::addMenu($parent_menu_id, 108, 'Allotments', 'fa-book', 'allotments', 'allotments_list');
        CommonMigrations::addMenu($parent_menu_id, 109, 'Allotment Cancellations', 'fa-times-rectangle', 'allotment-cancellations', 'allotment_cancellations_list');
        CommonMigrations::addMenu($parent_menu_id, 110, 'Possessions', 'fa-book', 'possessions', 'possessions_list');
        CommonMigrations::addMenu($parent_menu_id, 111, 'Possession Cancellations', 'fa-times-rectangle', 'possession-cancellations', 'possession_cancellations_list');
        CommonMigrations::addMenu($parent_menu_id, 112, 'Transfers', 'fa-book', 'transfers', 'transfers_list');

        $parent_menu_id = CommonMigrations::addMenu(0, 201, 'Accounting Setup', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 202, 'Account Heads', 'fa-cubes', 'account-heads', 'account_heads_list');
        CommonMigrations::addMenu($parent_menu_id, 203, 'Persons', 'fa-users', 'people', 'persons_list');

        $parent_menu_id = CommonMigrations::addMenu(0, 301, 'Accounting Management', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 302, 'Journal Vouchers', 'fa-list', 'journal-vouchers', 'journal_vouchers_list');
        CommonMigrations::addMenu($parent_menu_id, 303, 'Payments', 'fa-money', 'payments', 'payments_list');
        CommonMigrations::addMenu($parent_menu_id, 304, 'Receipts', 'fa-money', 'receipts', 'receipts_list');

        $parent_menu_id = CommonMigrations::addMenu(0, 401, 'Accounting Reports', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 402, 'Receivable/Payable Report', 'fa-money', 'receivables-payables-report', 'receivables_payables_report');
        CommonMigrations::addMenu($parent_menu_id, 403, 'Receivable/Payable Detail', 'fa-money', 'receivables-payables-detail-report', 'receivables_payables_detail_report');
        CommonMigrations::addMenu($parent_menu_id, 404, 'Ledger Report', 'fa-balance-scale', 'ledger-report', 'ledger_report');
        CommonMigrations::addMenu($parent_menu_id, 405, 'Trial Balance Report', 'fa-balance-scale', 'trial-balance-report', 'trial_balance_report');


        CommonMigrations::insertEntityPermission('Project', 'Projects', 'project', 'projects', 'projects');
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
