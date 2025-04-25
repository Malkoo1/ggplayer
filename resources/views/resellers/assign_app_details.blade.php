@extends('layouts.app')

@section('content')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Assign App Details</h3>
                        <div class="nk-block-des text-soft">
                            <p>Enter the details for app assignment.</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="{{ route('reseller.assign_app') }}" class="btn btn-outline-primary">
                                <em class="icon ni ni-arrow-left"></em>
                                <span>Back to Search</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <form action="{{ route('reseller.assign_app_process', $app->id) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="app_id">App ID</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="app_id" name="app_id"
                                                value="{{ $app->app_id }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="os">Operating System</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="os" name="os"
                                                value="{{ $app->os }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="licence_pkg">License Package</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select js-select2" id="licence_pkg" name="licence_pkg"
                                                aria-placeholder="Select Plan" required>
                                                <option value="">Select Plan</option>
                                                <option value="1 Month">1 Month</option>
                                                <option value="3 Month">3 Month</option>
                                                <option value="6 Month">6 Month</option>
                                                <option value="12 Month">12 Month</option>
                                                <option value="Unlimited">Unlimited</option>
                                            </select>
                                            <small class="text-muted">Credit cost will be deducted based on the selected
                                                plan</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="licence_expire">License Expiry Date</label>
                                        <div class="form-control-wrap">
                                            <input type="datetime-local" class="form-control" id="licence_expire"
                                                name="licence_expire" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="note">Notes</label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <strong>Your current credit balance: {{ Auth::user()->credit }}</strong>
                                        <p class="mb-0">Credit costs: 1 Month (1 credit), 3 Months (2 credits), 6 Months
                                            (3 credits), 12 Months (6 credits), Unlimited (20 credits)</p>
                                    </div>
                                </div>
                                <input type="hidden" id="credit_cost" name="credit_cost" value="0">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Assign App</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_content')
    <script>
        $(document).ready(function() {
            // Initialize select2
            $('.js-select2').select2();

            // Credit costs for different packages
            const creditCosts = {
                '1 Month': 1,
                '3 Month': 2,
                '6 Month': 3,
                '12 Month': 6,
                'Unlimited': 20
            };

            // Function to calculate expiry date and credit cost based on selected plan
            $('#licence_pkg').on('change', function() {
                const selectedPlan = $(this).val();
                let expiryDate = new Date();
                let creditCost = creditCosts[selectedPlan] || 0;

                // Show confirmation dialog for credit deduction
                if (creditCost > 0) {
                    if (!confirm(
                            `This will deduct ${creditCost} credit(s) from your account. Do you want to continue?`
                        )) {
                        $(this).val(''); // Reset selection if user cancels
                        return;
                    }
                }

                if (selectedPlan === '1 Month') {
                    expiryDate.setMonth(expiryDate.getMonth() + 1);
                } else if (selectedPlan === '3 Month') {
                    expiryDate.setMonth(expiryDate.getMonth() + 3);
                } else if (selectedPlan === '6 Month') {
                    expiryDate.setMonth(expiryDate.getMonth() + 6);
                } else if (selectedPlan === '12 Month') {
                    expiryDate.setMonth(expiryDate.getMonth() + 12);
                } else if (selectedPlan === 'Unlimited') {
                    // Set to a far future date (10 years from now)
                    expiryDate.setFullYear(expiryDate.getFullYear() + 10);
                }

                // Format the date for the datetime-local input
                const year = expiryDate.getFullYear();
                const month = String(expiryDate.getMonth() + 1).padStart(2, '0');
                const day = String(expiryDate.getDate()).padStart(2, '0');
                const hours = String(expiryDate.getHours()).padStart(2, '0');
                const minutes = String(expiryDate.getMinutes()).padStart(2, '0');

                const formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
                $('#licence_expire').val(formattedDate);

                // Set the credit cost value
                $('#credit_cost').val(creditCost);
            });
        });
    </script>
@endsection
