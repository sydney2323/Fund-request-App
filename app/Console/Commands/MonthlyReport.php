<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\UserRequest;
use App\Models\Project;
use App\Models\Category;
use App\Models\CategoryMonthlyBudget;
use App\Models\MonthlyBudget;
use App\Models\MonthlyUsedBudget;
use App\Models\File;
Use \Carbon\Carbon;
use PDF;

class MonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending Monthly report to all finance managers.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::now()->toDateString();
        $month_name = Carbon::parse($date)->format('F');
    
        $monthlyBudget = MonthlyBudget::where('month_name','=',$month_name)->get();
        if ($monthlyBudget->isEmpty()) {
            $this->info('no budget');
            return 0;
        }
        $id = $monthlyBudget[0]->id;
        $monthlyUsedBudget = MonthlyUsedBudget::where('monthly_budget_id','=',$id )->get(); 
        
        $categoryMonthlyBudgets = DB::table('categories')
        ->join('category_monthly_budgets', function ($join) use ($id){
            $join->on('categories.id', '=', 'category_monthly_budgets.category_id')
                ->where('category_monthly_budgets.monthly_budget_id', '=', $id);
        })
        ->get();

        $data = [
            'month_name' => $monthlyBudget[0]->month_name,
            'month_estimated_budget' => $monthlyBudget[0]->budget_amount,
            'month_used_budget' => $this->monthUsedBudget($id),
            'month_balance_budget' => $this->monthBalanceBudget($id, $monthlyBudget[0]->budget_amount),
           // 'total_amount_used_by_specific_user' => $this->totalAmountUsedBySpecificUser($id),
            'budget_for_categories' =>  $categoryMonthlyBudgets,
            'total_amount_used_in_specific_category' => $this->totalAmountUsedInSpecificCategory($id),
            'total_amount_used_in_specific_project' =>  $this->totalAmountUsedInSpecificProject($id),
        ];

        $pdf = PDF::loadView('monthlyReport', $data);
        $pdf = $pdf->output();

        $users = User::where('role','=','finance')->get();
        foreach ($users as $user)
        {
            Mail::raw('This is a monthly report.', function ($message) use ($user, $pdf, $month_name) {
                $message->subject($month_name.' monthly report');
                $message->to($user->email);
                $message->attachData($pdf,  $month_name.' monthlyReport.pdf', [
                    'mime' => 'application/pdf',
                ]);
            });
        }
    }

      /*-----------------------functions on how to generate report----------------------------------*/

      public function totalAmountUsedInSpecificProject($id){
        $totalAmountUsedInSpecificProject = MonthlyUsedBudget::where('monthly_budget_id','=',$id)
        ->select('project_id', DB::raw('SUM(amount) As total_amount'))
        ->groupBy('project_id')
         ->get();
         
        return $totalAmountUsedInSpecificProject;
    }

    public function totalAmountUsedInSpecificCategory($id){

        $totalAmountUsedInSpecificCategory = MonthlyUsedBudget::where('monthly_budget_id','=',$id)
        ->select('category_id', DB::raw('SUM(amount) As total_amount'))
        ->groupBy('category_id')
         ->get();
        return $totalAmountUsedInSpecificCategory;
        
    }

    // public function totalAmountUsedBySpecificUser($id){
    //     $totalAmountUsedBySpecificUser = MonthlyUsedBudget::where('monthly_budget_id','=',$id)
    //     ->select('staff_id', DB::raw('SUM(amount) As total_amount'))
    //     ->groupBy('staff_id')
    //      ->get();
    //     return $totalAmountUsedBySpecificUser; 
    // }

    public function monthBalanceBudget($id, $monthEstimatedBudget){
        $total = MonthlyUsedBudget::where('monthly_budget_id','=',$id)
        ->sum('amount');
        return $monthEstimatedBudget - $total;
    }

    public function monthUsedBudget($id){
        $total = MonthlyUsedBudget::where('monthly_budget_id','=',$id)
        ->sum('amount');
        return $total;
    }
}
