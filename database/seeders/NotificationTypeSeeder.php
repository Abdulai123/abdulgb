<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notificationTypes = [
            // News
            ['name' => 'New News Article Published', 'content' => 'A new news article has been published.', 'action' => 'created', 'icon' => 'news'],
            ['name' => 'News Article Updated', 'content' => 'An existing news article has been updated.', 'action' => 'updated', 'icon' => 'news'],
            ['name' => 'New System Announcement', 'content' => 'An important announcement from the system administrator.', 'action' => 'announcement', 'icon' => 'news'],
        
            // Order
            ['name' => 'New Order Created', 'content' => 'A new order has been created.', 'action' => 'created', 'icon' => 'order'],
            ['name' => 'Order Disputed', 'content' => 'A dispute has been initiated for your order.', 'action' => 'dispute', 'icon' => 'dispute'],
            ['name' => 'Order Dispatched', 'content' => 'Your order has been dispatched.', 'action' => 'dispatched', 'icon' => 'order'],
            ['name' => 'Order Completed', 'content' => 'Your order has been successfully completed.', 'action' => 'completed', 'icon' => 'order'],
            ['name' => 'Order Cancelled', 'content' => 'Your order has been cancelled.', 'action' => 'cancelled', 'icon' => 'order'],
            ['name' => 'Order Shipped', 'content' => 'Your order has been shipped.', 'action' => 'shipped', 'icon' => 'order'],
            ['name' => 'Order Delivered', 'content' => 'Your order has been delivered.', 'action' => 'delivered', 'icon' => 'order'],
            ['name' => 'Order Sent', 'content' => 'Your order has been sent.', 'action' => 'sent', 'icon' => 'order'],
            ['name' => 'Order Accepted', 'content' => 'Your order has been accepted by the store.', 'action' => 'accepted', 'icon' => 'order'],
            ['name' => 'Order Dispute Closed', 'content' => 'Order dispute has been closed.', 'action' => 'closed', 'icon' => 'dispute'],
            
        
            // Store
            ['name' => 'Store Encryption Key Generated', 'content' => 'A new encryption key has been generated for your store.', 'action' => 'key', 'icon' => 'store'],
            ['name' => 'Store Pending Approval', 'content' => 'Your store is pending approval for activation.', 'action' => 'pending', 'icon' => 'store'],
            ['name' => 'Store Approved', 'content' => 'Your store has been approved and is now active.', 'action' => 'approved', 'icon' => 'store'],
            ['name' => 'Store Rejected', 'content' => 'Your store activation request has been rejected, check your details and try again.', 'action' => 'rejected', 'icon' => 'store'],
            
            ['name' => 'Store Waiver Rejected', 'content' => 'Your store waiver activation request has been rejected, check your details and try again.', 'action' => 'waiver_rejected', 'icon' => 'store'],
            ['name' => 'Store Waiver Approved', 'content' => 'Your store waiver activation request has been approved, check your look for your encryption key and open a store.', 'action' => 'waiver_approved', 'icon' => 'store'],
        
            // Account
            ['name' => 'PGP Encryption Key Added', 'content' => 'A PGP encryption key has been added to your account.', 'action' => 'pgp', 'icon' => 'account'],
            ['name' => 'Store Verified', 'content' => 'Your store has been verified, keep up the good work!.', 'action' => 'verified', 'icon' => 'account'],
            ['name' => 'Account on Vacation', 'content' => 'Your account is on vacation mode.', 'action' => 'vacation', 'icon' => 'account'],
            ['name' => 'Account Warned', 'content' => 'A warning has been issued for your account.', 'action' => 'warning', 'icon' => 'account'],
            ['name' => 'Account Password Changed', 'content' => 'Your account password has been changed.', 'action' => 'changed', 'icon' => 'account'],
            ['name' => 'Private Mirror Added to Account', 'content' => 'A private mirror has been added to your account.', 'action' => 'added', 'icon' => 'account'],
        
            // Referral
            ['name' => 'Your Referral Link Used', 'content' => 'Your referral link has been used by a new user, thank you!.', 'action' => 'used', 'icon' => 'referral'],
            ['name' => 'Referral Payment Received', 'content' => 'You have received payment for a referral.', 'action' => 'received', 'icon' => 'referral'],
        
            // Listings
            ['name' => 'Favorited Product Updated', 'content' => 'One of your favorite product has been updated, check it out.', 'action' => 'update', 'icon' => 'product'],
            ['name' => 'Listing Restocked', 'content' => 'Your listing has been restocked.', 'action' => 'restocked', 'icon' => 'listing'],
            ['name' => 'New Listings Added by Store', 'content' => 'Your favorite store added new listings. Check them out!', 'action' => 'added', 'icon' => 'listing'],
        
            // Deposits and Withdrawals
            ['name' => 'New Incoming Deposit Received', 'content' => 'A new deposit has been detected in your account wallet.', 'action' => 'received', 'icon' => 'deposit'],
            ['name' => 'Deposit Confirmed and Added to Balance', 'content' => 'A deposit has been confirmed and added to your balance.', 'action' => 'confirmed', 'icon' => 'deposit'],
            ['name' => 'Deposit Request Cancelled', 'content' => 'A deposit request has been cancelled.', 'action' => 'cancelled', 'icon' => 'deposit'],
            ['name' => 'Deposit Request Rejected', 'content' => 'A deposit request has been rejected.', 'action' => 'rejected', 'icon' => 'deposit'],
            ['name' => 'New Withdrawal Request Submitted', 'content' => 'A new withdrawal request has been submitted.', 'action' => 'submitted', 'icon' => 'withdraw'],
            ['name' => 'Withdrawal Request In Progress', 'content' => 'A withdrawal request is in progress.', 'action' => 'in_progress', 'icon' => 'withdraw'],
            ['name' => 'Withdrawal Request Completed', 'content' => 'A withdrawal request has been completed.', 'action' => 'completed', 'icon' => 'withdraw'],
        
            // Shared Access
            ['name' => 'New Shared Access Given', 'content' => 'Shared access has been given to your account.', 'action' => 'given', 'icon' => 'shared'],
            ['name' => 'Shared Access Taken Away', 'content' => 'Shared access has been removed from your account.', 'action' => 'taken', 'icon' => 'shared'],
        
            // Other Events
            ['name' => 'Escrow Time Increased', 'content' => 'The escrow time for your order has been increased.', 'action' => 'increased', 'icon' => 'escrow'],
            ['name' => 'Percentage Refund Processed', 'content' => 'A percentage of your order has been refunded.', 'action' => 'partial_refund', 'icon' => 'escrow'],
            ['name' => 'Full Order Refund Processed', 'content' => 'Your order has been fully refunded.', 'action' => 'full_refund', 'icon' => 'escrow'],
        
            // Report
            ['name' => 'Product Reported', 'content' => 'A product has been reported by a user for a violation of market rules.', 'action' => 'reported', 'icon' => 'listing'],
            ['name' => 'Store Reported', 'content' => 'Your store has been reported by a user for a violation of market rules.', 'action' => 'reported', 'icon' => 'store'],
        
            // Bug
            ['name' => 'Bug Report Confirmed', 'content' => 'Your bug report has been confirmed and we are working hard to fix it, admin will message you.', 'action' => 'confirmed', 'icon' => 'bug'],
            ['name' => 'Bug Report Rejected', 'content' => 'Your bug report has been reviewed and rejected, thank you for your efforts.', 'action' => 'rejected', 'icon' => 'bug'],
            ['name' => 'New Bug Report Submitted', 'content' => 'A new bug report has been submitted.', 'action' => 'submitted', 'icon' => 'bug'],

            // cashback
            ['name' => '💰👈 Cashback For You', 'content' => 'Your order has been completed we have return 50% of our escrow profit to your account wallet, Thank you for using Whales Market! Want more money? invite friends.', 'action' => 'received', 'icon' => 'cashback'],

        ];        

        foreach ($notificationTypes as $type) {
            NotificationType::create($type);
        }
    }
}
