<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountHead extends CommonModel
{

    // RESERVED IDTS
    const IDT_ACCOUNT_RECEIVABLE_PAYABLE = 'account_receivable_payable';
    const IDT_LAND_COST = 'land_cost';
    const IDT_PROPERTY_SALES_INCOME = 'property_sales_income';
    const IDT_PROPERTY_SALES_COST = 'property_sales_cost';
    const IDT_FORM_PROCESSING_FEE_INCOME = 'form_processing_fee_income';
    const IDT_FORM_FINE_INCOME = 'fine_income';
    const IDT_DEALER_COMMISSION_EXPENSE = 'dealer_commission_expense';
    const IDT_BOOKING_CANCELLATION = 'booking_cancellation';

    const SALES_SERVICE_INCOME = "Sales / Service income";
    const OTHER_INCOME = "Other income";
    const INVENTORY = "Inventory";
    const FINISHED_GOODS = "Finished goods";
    const COST_DIRECT = "Cost direct";
    const COST_INDIRECT = "Cost indirect";
    const ADMINISTRATIVE_EXPENSES = "Administrative expenses";
    const SELLING_EXPENSES = "Selling expenses";
    const FINANCIAL_EXPENSES = "Financial expenses";
    const TAX_EXPENSE = "Tax expense";
    const NON_CURRENT_ASSETS = "Non-current assets";
    const CURRENT_ASSETS = "Current assets";
    const RECEIVABLE_PAYABLE = "Receivable / Payable";
    const CASH_BANK = "Cash / Bank";
    const NON_CURRENT_LIABILITIES = "Non-current liabilities";
    const CURRENT_LIABILITIES = "Current liabilities";
    const EQUITY_CAPITAL = "Equity capital";
    const EQUITY_RESERVES = "Equity reserves";

    const ACCOUNT_TYPES = [
        AccountHead::SALES_SERVICE_INCOME,
        AccountHead::OTHER_INCOME,
        AccountHead::INVENTORY,
        AccountHead::FINISHED_GOODS,
        AccountHead::COST_DIRECT,
        AccountHead::COST_INDIRECT,
        AccountHead::ADMINISTRATIVE_EXPENSES,
        AccountHead::SELLING_EXPENSES,
        AccountHead::FINANCIAL_EXPENSES,
        AccountHead::TAX_EXPENSE,
        AccountHead::NON_CURRENT_ASSETS,
        AccountHead::CURRENT_ASSETS,
        AccountHead::RECEIVABLE_PAYABLE,
        AccountHead::CASH_BANK,
        AccountHead::NON_CURRENT_LIABILITIES,
        AccountHead::CURRENT_LIABILITIES,
        AccountHead::EQUITY_CAPITAL,
        AccountHead::EQUITY_RESERVES,
    ];

    public static function getAccountByIdt($idt)
    {
        return self::where('idt', $idt)
            ->first();
    }

    public function searchForSelect($search_term, $where_clauses)
    {
        $data = parent::searchForSelect($search_term, $where_clauses)
            ->where('name', 'like', '%'.$search_term.'%');

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'Name: ' . $this->name . ', Type: ' . $this->type;
    }
}
