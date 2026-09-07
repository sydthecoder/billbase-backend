@if ($errors->any())
    <div class="mb-6 flex flex-col gap-1 bg-[#fef2f2] border border-[#fecaca] text-[#b91c1c] rounded-xl px-4 py-3 text-[14px]">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="flex flex-col gap-8">

    {{-- Company --}}
    <div class="bg-white rounded-2xl border border-[#e5e7eb] shadow-md p-6 md:p-8 flex flex-col gap-6">
        <div>
            <h3 class="text-[20px] font-bold tracking-tight text-dark-500 mb-1">Company Details</h3>
            <p class="text-[14px] text-[#6b7280]">Optional — fill this in if the customer is a registered business.</p>
        </div>

        <div class="w-full h-px bg-[#e5e7eb]"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2 md:col-span-2">
                <label class="text-[14px] font-medium text-dark-500" for="company_name">Company Name</label>
                <input
                    type="text"
                    id="company_name"
                    name="company_name"
                    value="{{ old('company_name', $customer->company_name ?? '') }}"
                    class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                >
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[14px] font-medium text-dark-500" for="company_reg_number">Company Reg Number</label>
                <input
                    type="text"
                    id="company_reg_number"
                    name="company_reg_number"
                    value="{{ old('company_reg_number', $customer->company_reg_number ?? '') }}"
                    class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                >
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[14px] font-medium text-dark-500" for="vat_number">VAT Number</label>
                <input
                    type="text"
                    id="vat_number"
                    name="vat_number"
                    value="{{ old('vat_number', $customer->vat_number ?? '') }}"
                    class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                >
            </div>
        </div>
    </div>

    {{-- Personal --}}
    <div class="bg-white rounded-2xl border border-[#e5e7eb] shadow-md p-6 md:p-8 flex flex-col gap-6">
        <div>
            <h3 class="text-[20px] font-bold tracking-tight text-dark-500 mb-1">Contact Person</h3>
            <p class="text-[14px] text-[#6b7280]">Who we'll reach out to for this customer.</p>
        </div>

        <div class="w-full h-px bg-[#e5e7eb]"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2">
                <label class="text-[14px] font-medium text-dark-500" for="first_name">First Name <span class="text-[#dc2626]">*</span></label>
                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    value="{{ old('first_name', $customer->first_name ?? '') }}"
                    required
                    class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                >
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[14px] font-medium text-dark-500" for="last_name">Last Name <span class="text-[#dc2626]">*</span></label>
                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    value="{{ old('last_name', $customer->last_name ?? '') }}"
                    required
                    class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                >
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[14px] font-medium text-dark-500" for="email">Email <span class="text-[#dc2626]">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $customer->email ?? '') }}"
                    required
                    class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                >
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[14px] font-medium text-dark-500" for="phone">Phone</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone', $customer->phone ?? '') }}"
                    class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                >
            </div>
        </div>
    </div>

    {{-- Address --}}
    <div class="bg-white rounded-2xl border border-[#e5e7eb] shadow-md p-6 md:p-8 flex flex-col gap-6">
        <div>
            <h3 class="text-[20px] font-bold tracking-tight text-dark-500 mb-1">Address</h3>
            <p class="text-[14px] text-[#6b7280]">Used on invoices and quotes for this customer.</p>
        </div>

        <div class="w-full h-px bg-[#e5e7eb]"></div>

        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <label class="text-[14px] font-medium text-dark-500" for="street_address">Street Address</label>
                <input
                    type="text"
                    id="street_address"
                    name="street_address"
                    value="{{ old('street_address', $customer->street_address ?? '') }}"
                    class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                >
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-medium text-dark-500" for="suburb">Suburb</label>
                    <input
                        type="text"
                        id="suburb"
                        name="suburb"
                        value="{{ old('suburb', $customer->suburb ?? '') }}"
                        class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                    >
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-medium text-dark-500" for="city">City</label>
                    <input
                        type="text"
                        id="city"
                        name="city"
                        value="{{ old('city', $customer->city ?? '') }}"
                        class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                    >
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-medium text-dark-500" for="province">Province</label>
                    <div class="relative">
                        <select
                            id="province"
                            name="province"
                            class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 appearance-none focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                        >
                            <option value="">— Select Province —</option>
                            @foreach (config('lookup.provinces') as $code => $label)
                                <option value="{{ $code }}" {{ old('province', $customer->province ?? '') === $code ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#6b7280]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-medium text-dark-500" for="postal_code">Postal Code</label>
                    <input
                        type="text"
                        id="postal_code"
                        name="postal_code"
                        value="{{ old('postal_code', $customer->postal_code ?? '') }}"
                        class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                    >
                </div>
            </div>
        </div>
    </div>

    {{-- Notes & Status --}}
    <div class="bg-white rounded-2xl border border-[#e5e7eb] shadow-md p-6 md:p-8 flex flex-col gap-6">
        <div>
            <h3 class="text-[20px] font-bold tracking-tight text-dark-500 mb-1">Notes & Status</h3>
            <p class="text-[14px] text-[#6b7280]">Internal notes and whether this customer is active.</p>
        </div>

        <div class="w-full h-px bg-[#e5e7eb]"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2 md:col-span-2">
                <label class="text-[14px] font-medium text-dark-500" for="notes">Notes</label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="3"
                    class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 placeholder-[#6b7280] focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                >{{ old('notes', $customer->notes ?? '') }}</textarea>
            </div>

            <div class="flex flex-col gap-2 max-w-xs">
                <label class="text-[14px] font-medium text-dark-500" for="status">Status</label>
                <div class="relative">
                    <select
                        id="status"
                        name="status"
                        class="w-full bg-white border border-[#d1d5db] rounded-xl px-4 py-3 text-[16px] text-dark-500 appearance-none focus:outline-none focus:border-primary-500 focus:ring-[1px] focus:ring-primary-500 transition-all"
                    >
                        <option value="active"   {{ old('status', $customer->status ?? 'active') === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $customer->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#6b7280]">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>