<?php

namespace SMD\Common\ReservationSystem\Helpers;

class GeneralHelper
{
    public static function isNullOrEmpty($value)
    {
        return is_null($value) || (is_string($value) && trim($value) === '') || (is_array($value) && empty($value));
    }

    public static function getUserFullName($user)
    {
        if (is_null($user)) {
            return '';
        }
        $firstName = $user->first_name ?? '';
        $lastName = $user->last_name ?? '';
        return trim($firstName . ' ' . $lastName);
    }

    public static function formatPhone($phone)
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    public static function formatCurrency($amount)
    {
        return '$' . number_format($amount, 2);
    }

    public static function formatDate($date, $format = 'Y-m-d')
    {
        return date($format, strtotime($date));
    }

    public static function getAppName()
    {
        return config('app.name', 'Laravel');
    }

    public static function getAppUrl()
    {
        return config('app.url', 'http://localhost');
    }

    public static function createStripeStandardAccount($user)
    {
        // Stub method - Stripe integration not available
        return null;
    }

    public static function createStripeCustomer($user)
    {
        // Stub method - Stripe integration not available
        return null;
    }

    public static function getStripeAccount($accountId)
    {
        // Stub method - Stripe integration not available
        return null;
    }

    public static function getStripeCustomer($customerId)
    {
        // Stub method - Stripe integration not available
        return null;
    }

    public static function getStripeConnectUrl($user, $returnUrl, $refreshUrl)
    {
        // Stub method - Stripe integration not available
        return '#';
    }

    public static function stripeCustomerUpdate($user)
    {
        // Stub method - Stripe integration not available
        // In production, this would create/update a Stripe customer
        return null;
    }

    public static function userNotification($user, $type, $reservation = null)
    {
        // Stub method - notification not available
        return null;
    }

    public static function userLog($type, $data = null, $exception = null)
    {
        // Stub method - logging not available
        return null;
    }

    public static function getExceptionJson($exception)
    {
        return json_encode([
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);
    }

    public static function saveCriteriaTypeRecording($id, $name, $data)
    {
        // Stub method
        return null;
    }

    public static function saveCriteriaRecording($id, $name, $data)
    {
        // Stub method
        return null;
    }

    public static function ReportDateRange($start, $end)
    {
        return $start . ' - ' . $end;
    }
}
