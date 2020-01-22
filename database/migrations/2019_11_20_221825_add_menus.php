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
        CommonMigrations::addMenu($parent_menu_id, 10, 'Projects', 'fa-plus', 'projects', 'projects_list');
        CommonMigrations::addMenu($parent_menu_id, 20, 'Phases', 'fa-gear', 'phases', 'phases_list');
        CommonMigrations::addMenu($parent_menu_id, 30, 'Blocks', 'fa-cubes', 'blocks', 'blocks_list');
        CommonMigrations::addMenu($parent_menu_id, 40, 'Property Types', 'fa-list', 'property-types', 'property_types_list');

        $parent_menu_id = CommonMigrations::addMenu(0, 1000, 'Properties Management', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 1010, 'Land Purchases', 'fa-file', 'land-purchases', 'land_purchases_list');
        CommonMigrations::addMenu($parent_menu_id, 1020, 'Property Files', 'fa-file', 'property-files', 'property_files_list');
        CommonMigrations::addMenu($parent_menu_id, 1030, 'Dealer Bookings', 'fa-book', 'dealer-bookings', 'dealer_bookings_list');
        CommonMigrations::addMenu($parent_menu_id, 1040, 'Dealer Booking Returns', 'fa-book', 'dealer-booking-returns', 'dealer_booking_returns_list');
        CommonMigrations::addMenu($parent_menu_id, 1050, 'Bookings', 'fa-book', 'bookings', 'bookings_list');
        CommonMigrations::addMenu($parent_menu_id, 1060, 'Booking Cancellations', 'fa-times-rectangle', 'booking-cancellations', 'booking_cancellations_list');
        CommonMigrations::addMenu($parent_menu_id, 1070, 'Allotments', 'fa-book', 'allotments', 'allotments_list');
        CommonMigrations::addMenu($parent_menu_id, 1080, 'Allotment Cancellations', 'fa-times-rectangle', 'allotment-cancellations', 'allotment_cancellations_list');
        CommonMigrations::addMenu($parent_menu_id, 1090, 'Possessions', 'fa-book', 'possessions', 'possessions_list');
        CommonMigrations::addMenu($parent_menu_id, 1100, 'Possession Cancellations', 'fa-times-rectangle', 'possession-cancellations', 'possession_cancellations_list');
        CommonMigrations::addMenu($parent_menu_id, 1110, 'Transfers', 'fa-book', 'transfers', 'transfers_list');

        $parent_menu_id = CommonMigrations::addMenu(0, 2000, 'Accounting Setup', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 2010, 'Account Heads', 'fa-cubes', 'account-heads', 'account_heads_list');
        CommonMigrations::addMenu($parent_menu_id, 2020, 'Persons', 'fa-users', 'people', 'persons_list');

        $parent_menu_id = CommonMigrations::addMenu(0, 3000, 'Accounting Management', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 3010, 'Journal Vouchers', 'fa-list', 'journal-vouchers', 'journal_vouchers_list');
        CommonMigrations::addMenu($parent_menu_id, 3020, 'Payments', 'fa-money', 'payments', 'payments_list');
        CommonMigrations::addMenu($parent_menu_id, 3030, 'Receipts', 'fa-money', 'receipts', 'receipts_list');

        $parent_menu_id = CommonMigrations::addMenu(0, 4000, 'Accounting Reports', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 4010, 'Receivable/Payable Report', 'fa-money', 'receivables-payables-report', 'receivables_payables_report');
        CommonMigrations::addMenu($parent_menu_id, 4020, 'Receivable/Payable Detail', 'fa-money', 'receivables-payables-detail-report', 'receivables_payables_detail_report');
        CommonMigrations::addMenu($parent_menu_id, 4030, 'Ledger Report', 'fa-balance-scale', 'ledger-report', 'ledger_report');
        CommonMigrations::addMenu($parent_menu_id, 4040, 'Trial Balance Report', 'fa-balance-scale', 'trial-balance-report', 'trial_balance_report');


        CommonMigrations::insertEntityPermissions('Project', 'Projects', 'project', 'projects', 'projects');
        CommonMigrations::insertEntityPermissions('Phase', 'Phases', 'phase', 'phases', 'phases');
        CommonMigrations::insertEntityPermissions('Block', 'Blocks', 'block', 'blocks', 'blocks');
        CommonMigrations::insertEntityPermissions('Property Type', 'Property Types', 'property_type', 'property_types', 'property-types');
        CommonMigrations::insertEntityPermissions('Land Purchase', 'Land Purchases', 'land_purchase', 'land_purchases', 'land-purchases');
        CommonMigrations::insertEntityPermissions('Property File', 'Property Files', 'property_file', 'property_files', 'property-files');
        CommonMigrations::insertEntityPermissions('Dealer Booking', 'Dealer Bookings', 'dealer_booking', 'dealer_bookings', 'dealer-bookings');
        CommonMigrations::insertEntityPermissions('Dealer Booking Return', 'Dealer Booking Returns', 'dealer_booking_return', 'dealer_booking_returns', 'dealer-booking-returns');
        CommonMigrations::insertEntityPermissions('Booking', 'Booking', 'booking', 'bookings', 'bookings');
        CommonMigrations::insertEntityPermissions('Booking Cancellation', 'Booking Cancellations', 'booking_cancellation', 'booking_cancellations', 'booking-cancellations');
        CommonMigrations::insertEntityPermissions('Allotment', 'Allotments', 'allotment', 'allotments', 'allotments');
        CommonMigrations::insertEntityPermissions('Allotment Cancellation', 'Allotment Cancellations', 'allotment_cancellation', 'allotment_cancellations', 'allotment-cancellations');
        CommonMigrations::insertEntityPermissions('Possession', 'Possessions', 'possession', 'possessions', 'possessions');
        CommonMigrations::insertEntityPermissions('Possession Cancellation', 'Possession Cancellations', 'possession_cancellation', 'possession_cancellations', 'possession-cancellations');
        CommonMigrations::insertEntityPermissions('Transfer', 'Transfers', 'transfer', 'transfers', 'transfers');
        CommonMigrations::insertEntityPermissions('Account Head', 'Account Heads', 'account_head', 'account_heads', 'account-heads');
        CommonMigrations::insertEntityPermissions('Person', 'Persons', 'person', 'persons', 'people');
        CommonMigrations::insertEntityPermissions('Journal Voucher', 'Journal Vouchers', 'journal_voucher', 'journal_vouchers', 'journal-vouchers');
        CommonMigrations::insertEntityPermissions('Payment', 'Payments', 'payment', 'payments', 'payments');
        CommonMigrations::insertEntityPermissions('Receipt', 'Receipts', 'receipt', 'receipts', 'receipts');

        CommonMigrations::insertPermission('Receivable/Payable Report', 'receivables_payables_report', 'GET', 'receivables-payables-report');
        CommonMigrations::insertPermission('Receivable/Payable Detail', 'receivables_payables_detail_report', 'GET', 'receivables-payables-detail-report');
        CommonMigrations::insertPermission('Ledger Report', 'ledger_report', 'GET', 'ledger-report');
        CommonMigrations::insertPermission('Trial Balance Report', 'trial_balance_report', 'GET', 'trial-balance-report');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenuByOrderRange(8, 4040);
        
    }
}
