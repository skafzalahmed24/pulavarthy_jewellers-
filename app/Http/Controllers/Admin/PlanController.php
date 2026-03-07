<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPlan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = InvestmentPlan::latest()->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'term' => 'required|string|max:255',
            'base_deposit' => 'required|string|max:255',
            'bonus_benefit' => 'required|string|max:255',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'is_popular' => 'boolean',
            'icon' => 'required|string|max:255',
            'button_text' => 'required|string|max:255',
            'scheme_prefix' => 'required|string|max:10|unique:investment_plans,scheme_prefix',
        ]);

        $data = $request->all();
        $data['is_popular'] = $request->has('is_popular');
        $data['features'] = array_filter($request->features ?? []);

        InvestmentPlan::create($data);

        return redirect()->route('admin.plans.index')->with('success', 'Investment plan created successfully.');
    }

    public function edit(string $id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, string $id)
    {
        $plan = InvestmentPlan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'term' => 'required|string|max:255',
            'base_deposit' => 'required|string|max:255',
            'bonus_benefit' => 'required|string|max:255',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'is_popular' => 'boolean',
            'icon' => 'required|string|max:255',
            'button_text' => 'required|string|max:255',
            'scheme_prefix' => 'required|string|max:10|unique:investment_plans,scheme_prefix,' . $plan->id,
        ]);

        $data = $request->all();
        $data['is_popular'] = $request->has('is_popular');
        $data['features'] = array_filter($request->features ?? []);

        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('success', 'Investment plan updated successfully.');
    }

    public function destroy(string $id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Investment plan deleted successfully.');
    }
}