@extends('global.layout')

@section('maincontent')
<div class="container mt-20 d-flex justify-content-center align-items-center bg-white shadow-sm rounded-3 p-5">
    <div class="container mt-5">
        <!-- Title -->
        <div class="mb-4">
            <h3 class="card-header fw-boldest fs-1 " style="color: #7c0101; ">LIST OF COMPANIES</h3>
        </div>

        <!-- Top Actions -->
        <div class="d-flex justify-content-between align-items-center mb-8">
            <div class="position-relative" style="max-width: 300px;">
                <!-- Input Field -->
                <input type="text" 
                    id="searchInput"
                    class="form-control form-control-solid ps-5 fw-boldest rounded-3 w-300px"
                    placeholder="&#xF52A; Search..." 
                    style="font-family: 'Bootstrap-icons', sans-serif;">
            </div>
            <div class="d-flex align-items-center justify-content-end gap-2">
                <!-- ADD USER Button -->
                 <a class="btn btn-primary rounded-3 fw-boldest btn-hover-rise" data-bs-toggle="modal" data-bs-target="#modal_add_company">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black" />
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                        </svg>
                    </span> 
                    ADD COMPANY
                </a>
            </div>
        </div>


        <!-- Table -->
        <div class="table-responsive" style="padding: 0; margin: 0;">
            <table class="table table-striped align-middle text-center gy-7 gs-7 w-100" 
                id="companies_table" 
                style="margin: auto;">
                <thead>
                    <tr class="fw-boldest text-gray-800 fs-5">
                        <th class="w-250px">COMPANY NAME</th>
                        <th class="w-150px">CONTACT PERSON</th>
                        <th class="w-100px">CONTACT NUMBER</th>
                        <th class="w-100px">EMAIL</th>
                        <th class="w-100px">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @if($company->isEmpty())
                    <tr>
                        <td colspan="3">No companies available.</td>
                    </tr>
                    @else

                    @foreach($company as $company)
                    <!-- Row -->
                    <tr id="company-row-{{ $company->id }}">
                        <td>{{ $company->company_name }}</td>
                        <td>{{ $company->contact_person }}</td>
                        <td>{{ $company->contact_number }}</td>
                        <td>{{ $company->email}}</td>
                        <td>
                            <!--begin::Menu-->
                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" 
                                data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-start">
                                MENU
                            </button>

                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" 
                                data-kt-menu="true">

                                <div class="menu-item px-3">
                                    <a class="menu-link px-3 editCompanyBtn" 
                                        data-id="{{ $company->id }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modal_edit_company">
                                        <i class="bi bi-pencil-square text-primary me-2"></i>View & Edit Details
                                    </a>
                                </div>

                                <div class="menu-item px-3">
                                    <a class="menu-link px-3 deleteBtn"
                                        data-id="{{ $company->id }}">
                                        <i class="bi bi-trash text-danger me-2"></i> Delete
                                    </a>
                                </div>
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-3">
            <ul class="pagination justify-content-center mt-5">
                <!-- Buttons are dynamically added by JavaScript -->
            </ul>
        </nav>
    </div>
    <!--begin::Modals-->
    <!--begin::Modal - Add Company-->
    <div class="modal fade" id="modal_add_company" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" action="#" id="modal_add_company_form" novalidate>
                    <input class="" type="hidden">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <h2 class="fw-boldest" data-kt-calendar="title" style="color: #7c0101;">ADD COMPANY</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-toggle="tooltip" title="Close" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body py-10 px-lg-17">
                        <div class="row mb-5">
                            <label for="company_name" class="form-label fw-bold required">Company Name</label>
                            <input type="text" class="form-control form-control-solid" id="add_company_name" placeholder="Enter Company Name">
                            <div class="invalid-feedback">Required field</div>
                        </div>

                        <div class="row mb-5">
                            <label for="company_contact_person" class="form-label fw-bold">Contact Person</label>
                            <input type="text" class="form-control form-control-solid" id="add_company_contact_person" placeholder="Enter Contact Person (Optional)">
                        </div>

                        <div class="row mb-5">
                            <label for="company_contact_number" class="form-label fw-bold">Contact Number</label>
                            <input type="number" 
                            class="form-control form-control-solid" 
                            id="add_company_contact_number" 
                            placeholder="Enter Contact Number (Optional)"
                            name="contact_number">
                        </div>

                        <div class="row mb-5">
                            <label for="company_email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control form-control-solid" id="add_company_email" placeholder="Enter Email (Optional)">
                        </div>
                    </div>
                    <!--end::Modal body-->

                    <!-- DOM element to store the route URL -->
                    <div id="route-config-com" data-url="{{ route('add_company') }}"></div>
                    
                    <!--begin::Modal footer-->
                    <div class="modal-footer justify-content-end">
                        <!--begin::Button-->
                        <button type="submit" 
                            class="btn btn-success me-2 addBtn" 
                            id="add_company_submit">
                            SAVE
                        </button>
                        <button type="reset" 
                            class="btn btn-light" 
                            data-bs-dismiss="modal">
                            CANCEL
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Modal - Add Company-->

    <!--begin::Modal - Edit Company-->
    <div class="modal fade" id="modal_edit_company" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" action="#" id="modal_edit_company_form" novalidate>
                    <input class="" type="hidden">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <h2 class="fw-boldest" data-kt-calendar="title" style="color: #7c0101;">EDIT COMPANY</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-toggle="tooltip" title="Close" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <!--end::Modal header-->

                    <!--begin::Modal body-->
                    <div class="modal-body py-10 px-lg-17">
                        <div class="row mb-5">
                            <label for="company_name" class="form-label fw-bold required">Company Name</label>
                            <input type="text" class="form-control form-control-solid" id="edit_company_name" placeholder="Enter Company Name"
                                value="sample">
                            <div class="invalid-feedback">Required field</div>
                        </div>

                        <div class="row mb-5">
                            <label for="company_contact_person" class="form-label fw-bold">Contact Person</label>
                            <input type="text" class="form-control form-control-solid" id="edit_company_contact_person" placeholder="Enter Contact Person (Optional)"
                                value="sample">
                        </div>

                        <div class="row mb-5">
                            <label for="company_contact_number" class="form-label fw-bold">Contact Number</label>
                            <input type="tel" class="form-control form-control-solid" id="edit_company_contact_number" placeholder="Enter Contact Number (Optional)"
                                value="09009090">
                        </div>

                        <div class="row mb-5">
                            <label for="company_email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control form-control-solid" id="edit_company_email" placeholder="Enter Email (Optional)"
                                value="sample">
                        </div>
                    </div>
                    <!--end::Modal body-->

                    <!--begin::Modal footer-->
                    <div class="modal-footer justify-content-end">
                        <!--begin::Button-->
                        <button type="submit" class="btn btn-success me-2 addBtn" id="edit_company_submit">
                            SAVE
                        </button>
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">CANCEL</button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Modal - Edit Company-->
    <!--end::Modals-->
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/companies.js') }}"></script>
@endpush
