@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.account'), 'route' => route('home'), 'class' => ''],
            ['name' => trans('general.profile'), 'route' => route('profile'), 'class' => 'active']
        ]
    ]
])

@php($user = request()->user())

@section('css')
    <link href="{{ asset('/plugins/croppie/croppie.css') }}" rel="stylesheet">
@stop

@section('title', trans('general.profile'))

@section('content_body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center mb-4">
                            <div class="profile-container mr-3">
                                <img src="{{ asset($user->profile_image) }}" width="80" height="80" alt="">
                                <div class="profile-overlay" onclick="showCroopie()"
                                     title="{{trans('profile.image.title')}}">
                                    <div class="profile-text"><i class="fa fa-picture-o"></i></div>
                                </div>
                            </div>
                            <div class="media-body">
                                <h3 class="mb-0">{{ user_full_name($user) }}</h3>
                                <p class="text-muted mb-0">{{ trans('user.roles.'.$user->role_id) }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="card card-profile text-center">
                                    <span class="mb-1 text-primary"><i class="icon-home"></i></span>
                                    <h3 class="mb-0">{{$reservations_count}}</h3>
                                    <p class="text-muted px-4">{{trans('general.reservations')}}</p>
                                </div>
                            </div>
                            @if($user->is_owner)
                                <div class="col">
                                    <div class="card card-profile text-center">
                                        <span class="mb-1 text-warning"><i class="fa fa-dollar"></i></span>
                                        <h3 class="mb-0">{{number_format($user->balance, 2, '.', '')}}</h3>
                                        <p class="text-muted">{{trans('profile.balance')}}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row mb-5">
                            <div class="col-{{$user->is_broker ? '12' : '6'}} text-center">
                                <button class="btn btn-danger px-5" data-toggle="modal" id="btn_edit_profile"
                                        data-target="#edit_profile_modal"
                                        title="{{trans('profile.edit_profile_title')}}">{{trans('general.edit')}}</button>
                            </div>

                            @if($user->is_broker == false)
                                @if($user_fee_config == null || $user_fee_config->stripe_account_completed == false)
                                    <div class="col-6 text-center">
                                        <a class="btn btn-primary px-3" id="btn_stripe_profile"
                                                title="Connect with Stripe"
                                                href="{{$stripe_url}}">Connect with Stripe
                                        </a>
                                    </div>
                                @else
                                    <div class="col-6 text-center">
                                        <p class="btn btn-success px-3 text-white">Stripe is connected</p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <h4>{{trans('profile.about_me')}}</h4>
                        <div class="row mb-2">
                            <div class="col-lg-12 col-xl-3">
                                <strong class="text-dark text-nowrap">{{trans('profile.phone')}}</strong>
                            </div>
                            <div class="col-lg-12 col-xl-9">{{format_phone_number($user->phone_number)}}</div>
                        </div>
                        {{--<div class="row mb-2">
                            <div class="col-lg-12 col-xl-3">
                                <strong class="text-dark">{{trans('user.pin')}}</strong>
                            </div>
                            <div class="col-lg-12 col-xl-9">{{$user->pin}}</div>
                        </div>--}}
                        <div class="row mb-2">
                            <div class="col-lg-12 col-xl-3">
                                <strong class="text-dark text-nowrap">{{trans('user.email')}}</strong>
                            </div>
                            <div class="col-lg-12 col-xl-9">{{$user->email}}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-12 col-xl-3">
                                <strong class="text-dark text-nowrap">{{ trans('profile.address') }}</strong>
                            </div>
                            <div class="col-lg-12 col-xl-9">
                                <p class="m-0">{{ $user->address_1 }}</p>
                                <p class="m-0">{{ $user->address_2 }}</p>
                                <p class="m-0">{{ user_address_2($user) }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-xl-3">
                                <strong class="text-dark text-nowrap">{{ trans('property.country') }}</strong>
                            </div>
                            <div class="col-lg-12 col-xl-9">{{$user->country or trans('general.n_a')}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" id="profile_tabs_content" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#profile_card"
                                   data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}">
                                    <span><i class="ti-credit-card"></i></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#profile_bank_account"
                                   data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}">
                                    <span><i class="fa fa-university"></i></span>
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content tabcontent-border">
                            <div class="tab-pane fade" id="profile_card" role="tabpanel">
                                <h5 class="card-title d-inline-block">{{trans('general.cards')}}</h5>
                                <button class="btn btn-sm btn-primary pull-right"
                                        onclick="addSource('#profile_card')">{{trans('general.add')}}</button>
                                <div class="accordion mt-2" id="profile_card_listing">
                                    <div class="loader spinner"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile_bank_account" role="tabpanel">
                                <h5 class="card-title d-inline-block">{{trans('general.bank_accounts')}}</h5>
                                <button class="btn btn-sm btn-primary pull-right"
                                        onclick="addSource('#profile_bank_account')">{{trans('general.add')}}</button>
                                <div class="accordion mt-2" id="profile_bank_account_listing">
                                    <div class="loader spinner"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('components.modal', ['set_default'=> true, 'form_files'=> true, 'modal_id' => 'edit_profile_source_modal', 'form' => true, 'modal_title' => trans('profile.format.source_title'), 'button_text' => trans('general.save')])
        <input type="hidden" name="source" id="edit_profile_source_modal_source"/>
        <input type="hidden" name="id" id="edit_profile_source_modal_source_id"/>
        <div data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}">
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="edit_profile_source_modal_exp_month">{{ trans('profile.card.exp_month') }}</label>
                        <select name="exp_month" class="form-control" id="edit_profile_source_modal_exp_month"
                                data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}">
                            @for($i =1; $i<=12;$i++)
                                <option value="{{$i}}">{{sprintf("%02d", $i)}}</option>
                            @endfor
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="edit_profile_source_modal_exp_year">{{ trans('profile.card.exp_year') }}</label>
                        <select name="exp_year" class="form-control" id="edit_profile_source_modal_exp_year"
                                data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}">
                            @for($i = date("Y"); $i<=date("Y") + 19;$i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="edit_profile_source_modal_name">{{ trans('profile.card.name') }}</label>
                        <input name="name" type="text" class="form-control" id="edit_profile_source_modal_name"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('profile.card.enter_name') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="edit_profile_source_modal_address_line1">{{ trans('property.address_1') }}</label>
                        <input name="address_line1" type="text" class="form-control"
                               id="edit_profile_source_modal_address_line1"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_address_1') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="edit_profile_source_modal_address_line2">{{ trans('property.address_2') }}</label>
                        <input name="address_line2" type="text" class="form-control"
                               id="edit_profile_source_modal_address_line2"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_address_2') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="edit_profile_source_modal_address_city">{{ trans('property.city') }}</label>
                        <input name="address_city" type="text" class="form-control"
                               id="edit_profile_source_modal_address_city"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_city') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="edit_profile_source_modal_address_zip">{{ trans('property.zipcode') }}</label>
                        <input name="address_zip" type="text" class="form-control"
                               id="edit_profile_source_modal_address_zip"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_zipcode') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="edit_profile_source_modal_address_state">{{ trans('property.state') }}</label>
                        <input name="address_state" type="text" class="form-control"
                               id="edit_profile_source_modal_address_state"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_state') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="edit_profile_source_modal_address_country">{{ trans('property.country') }}</label>
                        <input name="address_country" type="text" class="form-control"
                               id="edit_profile_source_modal_address_country"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_country') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
        </div>
        <div data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}">
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="edit_profile_source_modal_account_holder_type">{{ trans('profile.bank_account.account_holder_type') }}</label>
                        <select name="account_holder_type" class="form-control"
                                id="edit_profile_source_modal_account_holder_type"
                                data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}">
                            @foreach(SMD\Common\Stripe\Enums\AccountHolderType::TYPES as $type)
                                <option value="{{$type}}">{{trans('profile.bank_account.type_'.$type)}}</option>
                            @endforeach
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="edit_profile_source_modal_account_holder_name">{{ trans('profile.bank_account.account_holder_name') }}</label>
                        <input name="account_holder_name" type="text" class="form-control"
                               id="edit_profile_source_modal_account_holder_name"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}"
                               placeholder="{{ trans('profile.bank_account.account_holder_name') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['set_default'=> true, 'form_files'=> true, 'modal_id' => 'add_profile_source_modal', 'form' => true, 'modal_title' => trans('profile.format.source_title'), 'button_text' => trans('general.save')])
        <input type="hidden" name="source" id="add_profile_source_modal_source"/>
        <div data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}">
            <div class="form-row">
                <div class="col-9">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_number">{{ trans('profile.card.number') }}</label>
                        <input name="number" type="text" class="form-control"
                               id="add_profile_source_modal_number"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('profile.card.enter_number') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_cvc">{{ trans('profile.card.cvc') }}</label>
                        <input name="cvc" type="text" class="form-control"
                               id="add_profile_source_modal_cvc"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('profile.card.enter_cvc') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_exp_month">{{ trans('profile.card.exp_month') }}</label>
                        <select name="exp_month" class="form-control" id="add_profile_source_modal_exp_month"
                                data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}">
                            @for($i =1; $i<=12;$i++)
                                <option value="{{$i}}">{{sprintf("%02d", $i)}}</option>
                            @endfor
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_exp_year">{{ trans('profile.card.exp_year') }}</label>
                        <select name="exp_year" class="form-control" id="add_profile_source_modal_exp_year"
                                data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}">
                            @for($i = date("Y"); $i<=date("Y") + 19;$i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_name">{{ trans('profile.card.name') }}</label>
                        <input name="name" type="text" class="form-control" id="add_profile_source_modal_name"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('profile.card.enter_name') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_address_line1">{{ trans('property.address_1') }}</label>
                        <input name="address_line1" type="text" class="form-control"
                               id="add_profile_source_modal_address_line1"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_address_1') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_address_line2">{{ trans('property.address_2') }}</label>
                        <input name="address_line2" type="text" class="form-control"
                               id="add_profile_source_modal_address_line2"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_address_2') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_address_city">{{ trans('property.city') }}</label>
                        <input name="address_city" type="text" class="form-control"
                               id="add_profile_source_modal_address_city"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_city') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_address_zip">{{ trans('property.zipcode') }}</label>
                        <input name="address_zip" type="text" class="form-control"
                               id="add_profile_source_modal_address_zip"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_zipcode') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_address_state">{{ trans('property.state') }}</label>
                        <input name="address_state" type="text" class="form-control"
                               id="add_profile_source_modal_address_state"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_state') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_address_country">{{ trans('property.country') }}</label>
                        <input name="address_country" type="text" class="form-control"
                               id="add_profile_source_modal_address_country"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::CARD}}"
                               placeholder="{{ trans('property.enter_country') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
        </div>
        <div data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}">
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_account_holder_type">{{ trans('profile.bank_account.account_holder_type') }}</label>
                        <select name="account_holder_type" class="form-control"
                                id="add_profile_source_modal_account_holder_type"
                                data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}">
                            @foreach(SMD\Common\Stripe\Enums\AccountHolderType::TYPES as $type)
                                <option value="{{$type}}">{{trans('profile.bank_account.type_'.$type)}}</option>
                            @endforeach
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_account_holder_name">{{ trans('profile.bank_account.account_holder_name') }}</label>
                        <input name="account_holder_name" type="text" class="form-control"
                               id="add_profile_source_modal_account_holder_name"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}"
                               placeholder="{{ trans('profile.bank_account.account_holder_name') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_country">{{ trans('property.country') }}</label>
                        <select name="country" class="form-control" id="add_profile_source_modal_country"
                                data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}">
                            <option value="US">United States</option>
                            <option value="DR">Dominican Republic</option>
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_currency">{{ trans('profile.bank_account.currency') }}</label>
                        <select name="currency" class="form-control" id="add_profile_source_modal_currency"
                                data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}">
                            <option value="usd">USD</option>
                            <option value="dop">DOP</option>
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_routing_number">{{ trans('profile.bank_account.routing_number') }}</label>
                        <input name="routing_number" type="text" class="form-control"
                               id="add_profile_source_modal_routing_number"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}"
                               placeholder="{{ trans('profile.bank_account.routing_number') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_account_number">{{ trans('profile.bank_account.account_number') }}</label>
                        <input name="account_number" type="text" class="form-control"
                               id="add_profile_source_modal_account_number"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}"
                               placeholder="{{ trans('profile.bank_account.account_number') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="form-group mb-1">
                        <label for="add_profile_source_modal_account_number_confirmation">{{ trans('profile.bank_account.account_number_confirmation') }}</label>
                        <input name="account_number_confirmation" type="text" class="form-control"
                               id="add_profile_source_modal_account_number_confirmation"
                               data-source="{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}"
                               placeholder="{{ trans('profile.bank_account.account_number_confirmation') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['set_default'=> true, 'form_files'=> true, 'modal_id' => 'edit_profile_modal', 'form' => true, 'modal_title' => trans('profile.edit_profile_title'), 'button_text' => trans('general.save')])
        <div class="default-tab">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab"
                       href="#edit_profile_modal_tab_profile">{{trans('general.profile')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#edit_profile_modal_tab_address">{{trans('profile.address')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#edit_profile_modal_tab_credentials">{{trans('general.credentials')}}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="edit_profile_modal_tab_profile" role="tabpanel">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_profile_modal_first_name">{{ trans('user.first_name') }}</label>
                                <input name="first_name" type="text" class="form-control" data-tab="profile"
                                       id="edit_profile_modal_first_name" data-default="{{$user->first_name}}"
                                       placeholder="{{ trans('user.enter_first_name') }}" value="{{$user->first_name}}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_profile_modal_last_name">{{ trans('user.last_name') }}</label>
                                <input name="last_name" type="text" class="form-control" data-tab="profile"
                                       id="edit_profile_modal_last_name" data-default="{{$user->last_name}}"
                                       placeholder="{{ trans('user.enter_last_name') }}" value="{{$user->last_name}}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="edit_profile_modal_email">{{ trans('user.email') }}</label>
                        <input name="email" type="email" class="form-control" id="edit_profile_modal_email"
                               data-tab="profile" placeholder="{{$user->email}}">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_profile_modal_phone_number">{{ trans('user.phone_number') }}</label>
                                <input name="phone_number" type="text" class="form-control" data-tab="profile"
                                       id="edit_profile_modal_phone_number" placeholder="{{$user->phone_number}}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        @if($user->is_broker)
                            <div class="col">
                                <div class="form-group mb-1">
                                    <label for="edit_profile_modal_broker_cut">{{ trans('user.broker_cut') }}</label>
                                    <input name="broker_cut" type="text" class="form-control" data-tab="profile"
                                           id="edit_profile_modal_broker_cut" value="{{$user->broker_cut}}"
                                           data-default="{{$user->broker_cut}}"
                                           placeholder="{{trans('user.enter_broker_cut')}}">
                                    <div class="text-danger"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="edit_profile_modal_tab_address" role="tabpanel">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_profile_modal_address_2">{{ trans('property.address_1') }}</label>
                                <input name="address_1" type="text" class="form-control"
                                       id="edit_profile_modal_address_1" data-tab="address"
                                       placeholder="{{ trans('property.enter_address_1') }}"
                                       value="{{$user->address_1}}" data-default="{{$user->address_1}}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_profile_modal_address_2">{{ trans('property.address_2') }}</label>
                                <input name="address_2" type="text" class="form-control"
                                       id="edit_profile_modal_address_2" data-tab="address"
                                       placeholder="{{ trans('property.enter_address_2') }}"
                                       value="{{$user->address_2}}" data-default="{{$user->address_2}}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_profile_modal_city">{{ trans('property.city') }}</label>
                                <input name="city" type="text" class="form-control" id="edit_profile_modal_city"
                                       placeholder="{{ trans('property.enter_city') }}" data-tab="address"
                                       value="{{$user->city}}" data-default="{{$user->city}}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_profile_modal_state">{{ trans('property.state') }}</label>
                                <input name="state" type="text" class="form-control" id="edit_profile_modal_state"
                                       placeholder="{{ trans('property.enter_state') }}" data-tab="address"
                                       value="{{$user->state}}" data-default="{{$user->state}}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_profile_modal_zipcode">{{ trans('property.zipcode') }}</label>
                                <input name="zipcode" type="text" class="form-control" id="edit_profile_modal_zipcode"
                                       placeholder="{{ trans('property.enter_zipcode') }}" data-tab="address"
                                       value="{{$user->zipcode}}" data-default="{{$user->zipcode}}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="edit_profile_modal_country">{{ trans('property.country') }}</label>
                                <input name="country" type="text" class="form-control" id="edit_profile_modal_country"
                                       placeholder="{{ trans('property.enter_country') }}" data-tab="address"
                                       value="{{$user->country}}" data-default="{{$user->country}}">
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="edit_profile_modal_tab_credentials" role="tabpanel">
                    <div class="form-group mb-1">
                        <label for="edit_profile_modal_pin">{{ trans('user.pin') }}</label>
                        <input name="pin" type="text" class="form-control" id="edit_profile_modal_pin"
                               data-tab="credentials" placeholder="{{ trans('user.enter_pin_edit') }}">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="edit_profile_modal_password">{{ trans('user.password') }}</label>
                        <input name="password" type="password" class="form-control" id="edit_profile_modal_password"
                               placeholder="{{ trans('user.enter_password_edit') }}" data-tab="credentials">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="edit_profile_modal_password_confirmation">{{ trans('user.password_confirmation') }}</label>
                        <input name="password_confirmation" type="password" class="form-control"
                               id="edit_profile_modal_password_confirmation" data-tab="credentials"
                               placeholder="{{ trans('user.password_confirmation') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'delete_profile_source_modal', 'modal_class' => 'modal-danger', 'modal_title' => trans('profile.format.source_title'), 'button_class' => 'btn-danger', 'button_text' => trans('general.delete')])
    @endcomponent

    @component('components.modal', ['modal_id' => 'edit_profile_image_modal', 'form' => true, 'modal_title' => trans('profile.image.title'), 'button_text' => trans('property.btn.image.upload')])
        <h5>{{trans('property.image_add_title')}}</h5>
        <div class="input-group mb-3">
            <div class="custom-file">
                <input id="images_property_modal_file" type="file" class="custom-file-input"
                       onchange="imageChoose(this)">
                <label id="images_property_modal_file_label"
                       class="custom-file-label">{{trans('property.btn.image.choose')}}</label>
            </div>
        </div>
        <div id="edit_profile_image_modal_croppie"></div>
        <div class="profile-current-image d-flex justify-content-center">
            <img id="edit_profile_image_modal_current" src="{{ asset($user->profile_image) }}"/>
        </div>
    @endcomponent
@stop

@section('scripts')
    <script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/plugins/croppie/croppie.min.js') }}"></script>
    <script src="{{ asset('/js/rs-app.js') }}"></script>

    <script>
        window.listing = {};
        window.croppie = null;
        window.titles = {
            '{{\SMD\Common\Stripe\Enums\SourceType::CARD}}': '{{trans('profile.card.type')}}',
            '{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}': '{{trans('profile.bank_account.type')}}',
            'delete_{{\SMD\Common\Stripe\Enums\SourceType::CARD}}': '{{trans('profile.card.delete_text')}}',
            'delete_{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}': '{{trans('profile.bank_account.delete_text')}}',
        }
        window.template = {
            '{{\SMD\Common\Stripe\Enums\SourceType::CARD}}': '<div class="card">\n' +
                '    <div class="card-header" style="font-size:14px;cursor:default">\n' +
                '        <a href="javascript:void(0)" class="collapsed mb-0 text-dark" data-toggle="collapse" style="line-height:24px;"\n' +
                '            data-target="#collapse_card___index__" aria-expanded="false" aria-controls="collapse_card___index__">\n' +
                '            <i class="fa" aria-hidden="true"></i>\n' +
                '            <span class="font-weight-bold mr-3 text-uppercase">__brand__</span>\n' +
                '            <span class="mr-3">•••• __last4__</span>\n' +
                '            <span class="mr-3">__expires__</span>\n' +
                '        </a>\n' +
                '        <span class="pull-right">\n' +
                '            __default_source__\n' +
                '            __source_actions__\n' +
                '        </span>\n' +
                '    </div>\n' +
                '    <div id="collapse_card___index__" class="collapse" data-parent="#profile___source___listing">\n' +
                '        <div class="card-body">\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('general.name')}}</strong></div>\n' +
                '                <div class="col-md-9">__name__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('profile.number')}}</strong></div>\n' +
                '                <div class="col-md-9">•••• __last4__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('profile.card.expires')}}</strong></div>\n' +
                '                <div class="col-md-9">__expires__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('general.type')}}</strong></div>\n' +
                '                <div class="col-md-9 text-capitalize">__brand__ __funding__ {{trans('profile.card.type')}}</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3 d-flex align-items-center">\n' +
                '                    <strong>{{trans('profile.card.billing_address')}}</strong></div>\n' +
                '                <div class="col-md-9">__billing_address__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('profile.phone')}}</strong></div>\n' +
                '                <div class="col-md-9">__phone__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('user.email')}}</strong></div>\n' +
                '                <div class="col-md-9">__email__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('profile.card.origin')}}</strong></div>\n' +
                '                <div class="col-md-9">__country__</div>\n' +
                '            </div>\n' +
                '            __cvc_check__\n' +
                '            __address_line1_check__\n' +
                '            __address_zip_check__\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</div>',
            '{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}': '<div class="card">\n' +
                '    <div class="card-header" style="font-size:14px;cursor:default">\n' +
                '        <a href="javascript:void(0)" class="collapsed mb-0 text-dark" data-toggle="collapse" style="line-height:24px;"\n' +
                '            data-target="#collapse_card___index__" aria-expanded="false" aria-controls="collapse_card___index__">\n' +
                '            <i class="fa" aria-hidden="true"></i>\n' +
                '            <span class="font-weight-bold mr-3">__bank_name__</span>\n' +
                '            <span class="mr-3">••••__last4__</span>\n' +
                '            <span class="mr-3">__currency__</span>\n' +
                '        </a>\n' +
                '        <span class="pull-right">\n' +
                '            __default_source__\n' +
                '            __source_actions__\n' +
                '        </span>\n' +
                '    </div>\n' +
                '    <div id="collapse_card___index__" class="collapse" data-parent="#profile___source___listing">\n' +
                '        <div class="card-body">\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('profile.bank_account.bank_name')}}</strong></div>\n' +
                '                <div class="col-md-9">__bank_name__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('profile.bank_account.account_holder_name')}}</strong></div>\n' +
                '                <div class="col-md-9">__account_holder_name__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('profile.bank_account.account_holder_type')}}</strong></div>\n' +
                '                <div class="col-md-9 text-uppercase">__account_holder_type__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('profile.number')}}</strong></div>\n' +
                '                <div class="col-md-9">••••__last4__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('general.type')}}</strong></div>\n' +
                '                <div class="col-md-9">{{trans('profile.bank_account.type')}}</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3">\n' +
                '                    <strong>{{trans('profile.bank_account.currency')}}</strong></div>\n' +
                '                <div class="col-md-9">__currency__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('property.country')}}</strong></div>\n' +
                '                <div class="col-md-9">__country__</div>\n' +
                '            </div>\n' +
                '            <div class="row pb-1">\n' +
                '                <div class="col-md-3"><strong>{{trans('general.status')}}</strong></div>\n' +
                '                <div class="col-md-9">__status__</div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</div>',
            'btn': {
                'verify': '<a class="dropdown-item accordion-dropdown-item" href="javascript:void(0)" onclick="verifySource(\'__index__\', \'__source__\')">{{trans('general.verify')}}</a>',
                'actions': '<div class="btn-group btn-group-sm ml-2" role="group" aria-label="Button group with nested dropdown">\n' +
                    '            <button type="button" onclick="editSource(\'__index__\', \'__source__\')" class="btn btn-outline-info" style="padding:0.12rem 0.25rem !important;">\n' +
                    '                <i class="fa fa-pencil"></i> {{trans('general.edit')}}\n' +
                    '            </button>\n' +
                    '            <button type="button" onclick="deleteSource(\'__index__\', \'__source__\')" class="btn btn-outline-danger" style="padding:0.12rem 0.25rem !important;">\n' +
                    '                <i class="fa fa-times"></i> {{trans('general.delete')}}</button>\n' +
                    '            <div class="btn-group btn-group-sm" role="group">\n' +
                    '                <button id="btnGroupDrop___source_____index__" type="button" class="btn btn-outline-secondary"\n' +
                    '                        style="border-top-right-radius:0.2rem;border-bottom-right-radius:0.2rem;padding:0.12rem 0.25rem !important;"\n' +
                    '                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n' +
                    '                    <i class="icon-options"></i>\n' +
                    '                </button>\n' +
                    '                <div class="dropdown-menu dropdown-menu-right" style="background-color:rgb(118, 131, 143);" aria-labelledby="btnGroupDrop___source_____index__">\n' +
                    '                    <h6 class="dropdown-header accordion-dropdown-item">{{trans('general.actions')}}</h6>\n' +
                    '                    <a class="dropdown-item accordion-dropdown-item __disabled_source__" href="javascript:void(0)"\n' +
                    '                       onclick="setSourceDefault(\'__index__\', \'__source__\', \'__disabled_source__\')">{{trans('profile.set_default_source')}}</a>\n' +
                    '                    __verify_source__\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '        </div>',
                'default_source': '<span class="badge badge-primary">{{trans('general.default')}}</span>'
            },
            'check': '<div class="row pb-1"><div class="col-md-3"><strong>__text__</strong></div><div class="col-md-9 text-uppercase">__value__</div></div>',
            'address': '<p class="m-0">__paragraph__</p>'
        };

        function setSourceDefault(index, source, status) {
            if (status == '') {
                const item = window.listing[source][index];

                const form = {
                    'default_source': item['id']
                };

                RSApp.jsonPost('{{route('default_source')}}', form, true)
                    .done(function (data) {
                        if (data.done) {
                            RSApp.alert('{{trans('general.done')}}', '{{trans('profile.set_default_source_done')}}', 'success', false);
                            loadAccordionContent($('a[data-toggle="tab"][data-source="' + source + '"]'), source);
                        } else {
                            RSApp.alert('{{trans('general.error.error')}}', data.error, 'danger', false);
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        RSApp.alert('{{trans('general.error.error')}}', errorThrown, 'danger', false);
                    });
            }
        }

        function addSource(tabId) {
            const tab = $('a[href="' + tabId + '"]');

            setupSourceForm('{{trans('general.add')}}', 'add_profile_source_modal', tab.data('source'));

            $('#add_profile_source_modal_default_source_input').prop("checked", true).trigger('change');

            $('#add_profile_source_modal').modal('show');
        }

        function deleteSource(index, source) {
            const item = window.listing[source][index];

            const title = '{{trans('profile.format.source_title')}}'
                .replace(/:action/g, '{{trans('general.delete')}}')
                .replace(/:source/g, window.titles[source]);

            $('#delete_profile_source_modal_modal_label').text(title);

            $('#delete_profile_source_modal_button').data('source-id', item['id']);
            $('#delete_profile_source_modal_button').data('source', source);

            let html = '<p class="m-3 font-small">__text__</p>'
                .replace(/__text__/g, window.titles['delete_' + source]);

            $('#delete_profile_source_modal').find('.modal-body').html(html);

            $('#delete_profile_source_modal').modal('show');
        }

        function verifySource(index, source) {
            const item = window.listing[source][index];
            console.log('verify', source, index, item);

            const form = {
                'source': source,
                'source_id': item['id']
            };

            RSApp.jsonPost('{{route('verify_source')}}', form, true)
                .done(function (data) {
                    if (data.done) {
                        RSApp.alert('{{trans('general.done')}}', '{{trans('profile.verify_source_done')}}', 'success', false);
                        loadAccordionContent($('a[data-toggle="tab"][data-source="' + source + '"]'), source);
                    } else {
                        RSApp.alert('{{trans('general.error.error')}}', data.error, 'danger', false);
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    RSApp.alert('{{trans('general.error.error')}}', errorThrown, 'danger', false);
                });
        }

        function editSource(index, source) {
            const item = window.listing[source][index];

            setupSourceForm('{{trans('general.edit')}}', 'edit_profile_source_modal', source);

            $('#edit_profile_source_modal_source_id').val(item['id']);

            if (source == '{{\SMD\Common\Stripe\Enums\SourceType::CARD}}') {
                $('#edit_profile_source_modal_exp_month').val(item['exp_month']);
                $('#edit_profile_source_modal_exp_year').val(item['exp_year']);
                $('#edit_profile_source_modal_name').val(item['name']);
                $('#edit_profile_source_modal_address_line1').val(item['address_line1']);
                $('#edit_profile_source_modal_address_line2').val(item['address_line2']);
                $('#edit_profile_source_modal_address_city').val(item['address_city']);
                $('#edit_profile_source_modal_address_zip').val(item['address_zip']);
                $('#edit_profile_source_modal_address_state').val(item['address_state']);
                $('#edit_profile_source_modal_address_country').val(item['address_country']);
            }

            if (source == '{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}') {
                $('#edit_profile_source_modal_account_holder_name').val(item['account_holder_name']);
                $('#edit_profile_source_modal_account_holder_type').val(item['account_holder_type']);
            }

            $('#edit_profile_source_modal').modal('show');
        }

        function setupSourceForm(action, modalId, source) {
            const title = '{{trans('profile.format.source_title')}}'
                .replace(/:source/g, window.titles[source])
                .replace(/:action/g, action);

            $('#' + modalId + '_source').val(source);
            $('#' + modalId + '_modal_label').text(title);

            $('#' + modalId + '_form div[data-source]').css({'display': 'none'});
            $('#' + modalId + '_form div[data-source="' + source + '"]').css({'display': 'block'});

            $('#' + modalId + '_form :input:not(:button):not([type="hidden"]):not([type="checkbox"])').attr('disabled', 'disabled');
            $('#' + modalId + '_form :input[data-source="' + source + '"]').removeAttr('disabled');
        }

        function cardBillingAddress(item) {
            let address = null;
            const template = window.template['address'];

            if (item['address_line1']) {
                address = template
                    .replace(/__paragraph__/g, item['address_line1']);

                if (item['address_line2']) {
                    address += template
                        .replace(/__paragraph__/g, item['address_line2']);
                }

                let parts = [];
                parts.push(item['address_city']);
                parts.push(item['address_state']);
                parts.push(item['address_zip']);
                parts.push(item['address_country']);
                parts = parts.filter(function (value) {
                    return value != null;
                });

                if (parts.length > 0) {
                    address += template.replace(/__paragraph__/g, parts.join(', '));
                }
            }

            return address;
        }

        function checkSourceProperty(value, text) {
            if (value) {
                return window.template['check']
                    .replace(/__text__/g, text)
                    .replace(/__value__/g, value);
            }

            return '';
        }

        function createAccordionElement(item, index, source) {
            let template = window.template[source]
                .replace(/__default_source__/g, btnSource('default_source', item['id'], item['default_source']))
                .replace(/__source_actions__/g, window.template['btn']['actions'])
                .replace(/__disabled_source__/g, (item['id'] == item['default_source'] ? 'disabled' : ''));

            if (source == '{{\SMD\Common\Stripe\Enums\SourceType::CARD}}') {
                return template
                    .replace(/__verify_source__/g, '')
                    .replace(/__source__/g, source)
                    .replace(/__index__/g, index)
                    .replace(/__brand__/g, item['brand'])
                    .replace(/__last4__/g, item['last4'])
                    .replace(/__funding__/g, item['funding'])
                    .replace(/__country__/g, item['country'])
                    .replace(/__name__/g, (item['name'] || '{{trans('profile.card.no_name')}}'))
                    .replace(/__phone__/g, (item['phone'] || '{{trans('profile.card.no_phone')}}'))
                    .replace(/__email__/g, (item['email'] || '{{trans('profile.card.no_email')}}'))
                    .replace(/__expires__/g, (("0" + item['exp_month']).slice(-2)) + ' / ' + item['exp_year'])
                    .replace(/__billing_address__/g, (cardBillingAddress(item) || '{{trans('profile.card.no_address')}}'))
                    .replace(/__cvc_check__/g, checkSourceProperty(item['cvc_check'], '{{trans('profile.card.cvc_check')}}'))
                    .replace(/__address_line1_check__/g, checkSourceProperty(item['address_line1_check'], '{{trans('profile.card.street_check')}}'))
                    .replace(/__address_zip_check__/g, checkSourceProperty(item['address_zip_check'], '{{trans('profile.card.zip_check')}}'));
            }

            if (source == '{{\SMD\Common\Stripe\Enums\SourceType::BANK_ACCOUNT}}') {
                return template
                    .replace(/__verify_source__/g, btnSource('verify', item['status'], 'verified', '!='))
                    .replace(/__source__/g, source)
                    .replace(/__index__/g, index)
                    .replace(/__bank_name__/g, item['bank_name'])
                    .replace(/__account_holder_name__/g, item['account_holder_name'])
                    .replace(/__account_holder_type__/g, item['account_holder_type'])
                    .replace(/__last4__/g, item['last4'])
                    .replace(/__currency__/g, '<span class="text-uppercase">' + item['currency'] + '</span>')
                    .replace(/__country__/g, item['country'])
                    .replace(/__status__/g, statusPill(item['status'], ''));
            }
        }

        function statusPill(status, field) {
            return status;
        }

        function btnSource(btnName, btnValue, value, match = '==') {
            if ((value != btnValue && match == '!=') || (value == btnValue && match == '==')) {
                return window.template['btn'][btnName];
            }
            return '';
        }

        function loadAccordionContent(tab, source) {
            if (source) {
                const accordion = $(tab.attr('href') + '_listing');

                RSApp.jsonGet(
                    '{{route('sources')}}/' + source,
                    function () {
                        accordion.html('<div class="loader spinner"></div>');
                        window.listing[source] = [];
                    })
                    .done(function (response) {
                        if (response.done && response.data.length > 0) {
                            accordion.html('');

                            $.each(response.data, function (i, item) {
                                item['default_source'] = response.default_source;
                                accordion.append(createAccordionElement(item, i, source));
                            });

                            window.listing[source] = response.data;
                        } else {
                            if (!response.done) {
                                RSApp.alert('{{trans('general.error.error')}}', response.error, 'danger', false);
                            }

                            accordion.html('<p class="text-center m-2">{{trans('profile.no_sources_found')}}</p>');
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        RSApp.alert('{{trans('general.error.error')}}', errorThrown, 'danger', false);

                        accordion.html('<p class="text-center m-2">{{trans('profile.no_sources_found')}}</p>');
                    });
            }
        }

        function showCroopie() {
            $('#edit_profile_image_modal_croppie').css({display: 'none'});
            $('#edit_profile_image_modal_current').css({display: 'block'});
            $('#edit_profile_image_modal_button').attr('disabled', 'disabled');
            if (window.croppie) {
                window.croppie.croppie('destroy');
                window.croppie = null;
            }
            $('#edit_profile_image_modal').modal('show');
        }

        function imageChoose(element) {
            $('#edit_profile_image_modal_current').css({display: 'none'});
            $('#edit_profile_image_modal_croppie').css({display: 'block'});
            $('#edit_profile_image_modal_button').removeAttr('disabled');

            if (window.croppie) {
                window.croppie.croppie('destroy');
                window.croppie = null;
            }

            window.croppie = $('#edit_profile_image_modal_croppie').croppie({
                viewport: {
                    width: 300,
                    height: 300,
                    type: 'circle'
                },
                boundary: {
                    width: 450,
                    height: 300
                }
            });

            readFile(element);
        }

        function readFile(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    window.croppie.croppie('bind', {
                        url: e.target.result
                    }).then(function () {
                        console.log('jQuery bind complete');
                    });
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                swal("Sorry - you're browser doesn't support the FileReader API");
            }
        }

        (function ($) {
            $('#edit_profile_modal_form').submit(function (e) {
                $('#edit_profile_modal_alert_danger').fadeOut();
                $('#edit_profile_modal_alert_success').fadeOut();
                if (!e.isDefaultPrevented()) {
                    e.preventDefault();
                    RSApp.jsonPost("{{ route('profile_edit') }}", this, false, function () {
                        $('#edit_profile_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#edit_profile_modal_alert_success').html('{{ trans('profile.edited') }}').fadeIn();
                                setTimeout(function () {
                                    window.location.reload();
                                }, 750);
                            } else {
                                $('#edit_profile_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'edit_profile_modal_');
                            } else {
                                $('#edit_profile_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#edit_profile_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $('#delete_profile_source_modal_button').on('click', function (e) {

                const source = $(this).data('source');

                const form = {
                    'source': source,
                    'source_id': $(this).data('source-id'),
                };

                $('#delete_profile_source_modal').modal('hide');

                RSApp.jsonPost('{{route('delete_source')}}', form, true)
                    .done(function (data) {
                        if (data.done) {
                            RSApp.alert('{{trans('general.done')}}', '{{trans('profile.delete_default_source_done')}}', 'success', false);
                            loadAccordionContent($('a[data-toggle="tab"][data-source="' + source + '"]'), source);
                        } else {
                            RSApp.alert('{{trans('general.error.error')}}', data.error, 'danger', false);
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR, textStatus, errorThrown);
                        RSApp.alert('{{trans('general.error.error')}}', errorThrown, 'danger', false);
                    });
            });

            $('#edit_profile_source_modal_form').submit(function (e) {
                $('#edit_profile_source_modal_alert_danger').fadeOut();
                $('#edit_profile_source_modal_alert_success').fadeOut();
                if (!e.isDefaultPrevented()) {
                    e.preventDefault();
                    RSApp.jsonPost("{{ route('edit_source') }}", this, false, function () {
                        $('#edit_profile_source_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#edit_profile_source_modal').modal('hide');
                                const source = $("#edit_profile_source_modal_source").val();
                                loadAccordionContent($('a[data-toggle="tab"][data-source="' + source + '"]'), source);
                                RSApp.clearForm('edit_profile_source_modal');
                            } else {
                                $('#edit_profile_source_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'edit_profile_source_modal_');
                            } else {
                                $('#edit_profile_source_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#edit_profile_source_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $('#add_profile_source_modal_form').submit(function (e) {
                $('#add_profile_source_modal_alert_danger').fadeOut();
                $('#add_profile_source_modal_alert_success').fadeOut();
                if (!e.isDefaultPrevented()) {
                    e.preventDefault();
                    RSApp.jsonPost("{{ route('add_source') }}", this, false, function () {
                        $('#add_profile_source_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#add_profile_source_modal').modal('hide');
                                const source = $("#add_profile_source_modal_source").val();
                                loadAccordionContent($('a[data-toggle="tab"][data-source="' + source + '"]'), source);
                                RSApp.clearForm('add_profile_source_modal');
                            } else {
                                $('#add_profile_source_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'add_profile_source_modal_');
                            } else {
                                $('#add_profile_source_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#add_profile_source_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $('#edit_profile_image_modal_form').on('submit', function (e) {
                e.preventDefault();
                if (e.isDefaultPrevented) {
                    window.croppie.croppie('result', {type: 'canvas'})
                        .then(function (base_64) {
                            const form = {
                                base_64
                            };

                            RSApp.jsonPost('{{route('profile_image')}}', form, true, function () {
                                $('#edit_profile_image_modal_form :input').attr('disabled', 'disabled');
                            })
                                .done(function (data) {
                                    console.log(data);
                                })
                                .fail(function (jqXHR, textStatus, errorThrown) {
                                    console.log(textStatus, errorThrown);
                                })
                                .always(function () {
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 750);
                                });
                        });
                }
            });

            $('#profile_tabs_content a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                loadAccordionContent($(this), $(this).data('source'));
            });

            $('a[href="#profile_card"]').tab('show');
        })(jQuery);
    </script>

@endsection