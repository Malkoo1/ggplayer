@extends('layouts.app')

@section('content')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Edit App Record</h3>
                        <div class="nk-block-des text-soft">
                            <p>Update app record details.</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="{{ route('reseller.dashboard') }}" class="btn btn-outline-primary">
                                <em class="icon ni ni-arrow-left"></em>
                                <span>Back to Dashboard</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <div class="card card-bordered">
                    <div class="card-inner">
                        @if (Session::has('success'))
                            <div class="alert alert-success mt-4">
                                <strong>{{ Session::get('success') }}</strong>
                            </div>
                        @endif
                        @if (Session::has('fail'))
                            <div class="alert alert-danger mt-4">
                                <strong>{{ Session::get('fail') }}</strong>
                            </div>
                        @endif
                        <form action="{{ route('reseller.update_app', $app->id) }}" method="POST">
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
                                        <label class="form-label" for="assign_url">Assign URL</label>
                                        <div class="form-control-wrap">
                                            <input type="url" class="form-control" id="assign_url" name="assign_url"
                                                value="{{ $app->assign_url }}" placeholder="Enter Assign URL">
                                            @error('assign_url')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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
                                                <option value="1 Month"
                                                    {{ $app->licence_pkg === '1 Month' ? 'selected' : '' }}>1 Month</option>
                                                <option value="3 Month"
                                                    {{ $app->licence_pkg === '3 Month' ? 'selected' : '' }}>3 Month</option>
                                                <option value="6 Month"
                                                    {{ $app->licence_pkg === '6 Month' ? 'selected' : '' }}>6 Month
                                                </option>
                                                <option value="12 Month"
                                                    {{ $app->licence_pkg === '12 Month' ? 'selected' : '' }}>12 Month
                                                </option>
                                                <option value="Unlimited"
                                                    {{ $app->licence_pkg === 'Unlimited' ? 'selected' : '' }}>Unlimited
                                                </option>
                                            </select>
                                            @error('licence_pkg')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="licence_expire">License Expiry Date</label>
                                        <div class="form-control-wrap">
                                            <input type="datetime-local" class="form-control" id="licence_expire"
                                                name="licence_expire"
                                                value="{{ date('Y-m-d\TH:i', strtotime($app->licence_expire)) }}" required>
                                            @error('licence_expire')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="note">Notes</label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control" id="note" name="note" rows="3">{{ $app->note }}</textarea>
                                            @error('note')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update App Record</button>
                                </div>
                                <input type="hidden" id="credit_cost" name="credit_cost" value="0">
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
                console.log('Credit cost set to:', creditCost);
            });
        });
    </script>
@endsection
