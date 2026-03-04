<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\Investment;
use App\Models\Loan;
use App\Models\Repayment;
use Carbon\Carbon;

class KikundiSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Kikundi seeder...');
        
        // Create Members
        $members = [
            [
                'member_id' => 'M001',
                'full_name' => 'John Mwangi',
                'phone_number' => '0712345678',
                'member_type' => 'Student',
                'date_joined' => '2025-01-01',
                'status' => 'Active',
                'notes' => 'Regular investor'
            ],
            [
                'member_id' => 'M002',
                'full_name' => 'Mary Nyambura',
                'phone_number' => '0723456789',
                'member_type' => 'Worker',
                'date_joined' => '2025-01-01',
                'status' => 'Active',
                'notes' => 'Takes loans regularly'
            ],
            [
                'member_id' => 'M003',
                'full_name' => 'Peter Kamau',
                'phone_number' => '0734567890',
                'member_type' => 'Worker',
                'date_joined' => '2025-01-05',
                'status' => 'Active',
                'notes' => 'Investor only'
            ],
            [
                'member_id' => 'M004',
                'full_name' => 'Grace Wanjiru',
                'phone_number' => '0745678901',
                'member_type' => 'Student',
                'date_joined' => '2025-01-10',
                'status' => 'Active',
                'notes' => 'New member'
            ],
            [
                'member_id' => 'M005',
                'full_name' => 'James Omondi',
                'phone_number' => '0756789012',
                'member_type' => 'Worker',
                'date_joined' => '2025-01-15',
                'status' => 'Active',
                'notes' => 'Active borrower'
            ],
        ];

        foreach ($members as $memberData) {
            Member::updateOrCreate(
                ['member_id' => $memberData['member_id']], 
                $memberData
            );
        }
        $this->command->info('✓ Members seeded');
        $this->command->info('✓ Members seeded');

        // Get member IDs
        $member1 = Member::where('member_id', 'M001')->first();
        $member2 = Member::where('member_id', 'M002')->first();
        $member3 = Member::where('member_id', 'M003')->first();
        $member4 = Member::where('member_id', 'M004')->first();
        $member5 = Member::where('member_id', 'M005')->first();

        // Create Investments
        $investments = [
            ['transaction_id' => 'I001', 'member_id' => $member1->id, 'amount' => 5000, 'investment_date' => '2025-01-15', 'quarter' => 'Q1', 'year' => 2025],
            ['transaction_id' => 'I002', 'member_id' => $member2->id, 'amount' => 50000, 'investment_date' => '2025-01-15', 'quarter' => 'Q1', 'year' => 2025],
            ['transaction_id' => 'I003', 'member_id' => $member3->id, 'amount' => 100000, 'investment_date' => '2025-01-20', 'quarter' => 'Q1', 'year' => 2025],
            ['transaction_id' => 'I004', 'member_id' => $member4->id, 'amount' => 10000, 'investment_date' => '2025-01-25', 'quarter' => 'Q1', 'year' => 2025],
            ['transaction_id' => 'I005', 'member_id' => $member5->id, 'amount' => 75000, 'investment_date' => '2025-01-28', 'quarter' => 'Q1', 'year' => 2025],
        ];

        foreach ($investments as $investmentData) {
            Investment::updateOrCreate(
                ['transaction_id' => $investmentData['transaction_id']], 
                $investmentData
            );
        }
        $this->command->info('✓ Investments seeded');

        // Create Loans
        $loans = [
            [
                'loan_id' => 'L001',
                'member_id' => $member2->id,
                'loan_amount' => 30000,
                'loan_date' => '2025-01-25',
                'status' => 'Active',
                'notes' => 'First loan'
            ],
            [
                'loan_id' => 'L002',
                'member_id' => $member5->id,
                'loan_amount' => 50000,
                'loan_date' => '2025-01-30',
                'status' => 'Active',
                'notes' => 'Business expansion'
            ],
        ];

        foreach ($loans as $loanData) {
            Loan::updateOrCreate(
                ['loan_id' => $loanData['loan_id']], 
                $loanData
            );
        }
        $this->command->info('✓ Loans seeded');

        // Get loan IDs
        $loan1 = Loan::where('loan_id', 'L001')->first();
        $loan2 = Loan::where('loan_id', 'L002')->first();

        // Create Repayments
        $repayments = [
            [
                'repayment_id' => 'R001',
                'loan_id' => $loan1->id,
                'member_id' => $member2->id,
                'amount' => 10000,
                'payment_date' => '2025-02-15',
                'notes' => 'First installment'
            ],
            [
                'repayment_id' => 'R002',
                'loan_id' => $loan2->id,
                'member_id' => $member5->id,
                'amount' => 15000,
                'payment_date' => '2025-02-20',
                'notes' => 'Partial payment'
            ],
        ];

        foreach ($repayments as $repaymentData) {
            Repayment::updateOrCreate(
                ['repayment_id' => $repaymentData['repayment_id']], 
                $repaymentData
            );
        }
        $this->command->info('✓ Repayments seeded');

        $this->command->info('');
        $this->command->info('✅ Kikundi sample data seeded successfully!');
        $this->command->info('');
        $this->command->info('Summary:');
        $this->command->info('- Members: ' . Member::count());
        $this->command->info('- Investments: ' . Investment::count());
        $this->command->info('- Loans: ' . Loan::count());
        $this->command->info('- Repayments: ' . Repayment::count());
    }
}
