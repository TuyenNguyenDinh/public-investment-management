@php use App\Enums\BaseEnum;$user=auth()->user(); @endphp
<div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
    <div class="card mb-6">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap row-gap-2">
            <div class="card-title m-0">
                <h5 class="m-0">{{ __('email_configuration') }}</h5>
                <p class="my-0 card-subtitle">{{ __('configure_email_settings') }}</p>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('app-configs-update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label for="emailProtocol" class="form-label">{{ __('email_protocol') }}</label>
                    <select class="form-select select2" id="emailProtocol" name="email_protocol">
                        <option value="smtp"
                                @if($config->get('email_protocol') === "smtp") selected @endif>{{ __('smtp') }}</option>
                        <option value="sendmail"
                                @if($config->get('email_protocol') === "sendmail") selected @endif>{{ __('sendmail') }}</option>
                        <option value="mail"
                                @if($config->get('email_protocol') === "mail") selected @endif>{{ __('mail') }}</option>
                    </select>
                    @error('email_protocol')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="fromName" class="form-label">{{ __('from_name') }}</label>
                    <input type="text" class="form-control" id="fromName" name="from_name"
                           placeholder="{{ __('from_name_placeholder') }}"
                           value="{{ old('from_name', $config->get('from_name')) }}"/>
                    @error('from_name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="fromAddress" class="form-label">{{ __('from_address') }}</label>
                    <input type="email" class="form-control" id="fromAddress" name="from_address"
                           placeholder="{{ __('from_address_placeholder') }}"
                           value="{{ old('from_address', $config->get('from_address')) }}"/>
                    @error('from_address')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="smtpServer" class="form-label">{{ __('smtp_server') }}</label>
                    <input type="text" class="form-control" id="smtpServer" name="smtp_server"
                           placeholder="{{ __('smtp_server_placeholder') }}"
                           value="{{ old('smtp_server', $config->get('smtp_server')) }}"/>
                    @error('smtp_server')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="smtpUser" class="form-label">{{ __('smtp_user') }}</label>
                    <input type="text" class="form-control" id="smtpUser" name="smtp_user"
                           placeholder="{{ __('smtp_user_placeholder') }}"
                           value="{{ old('smtp_user', $config->get('smtp_user')) }}"/>
                    @error('smtp_user')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="smtpPassword" class="form-label">{{ __('smtp_password') }}</label>
                    <input type="password" class="form-control" id="smtpPassword" name="smtp_password"
                           placeholder="{{ __('smtp_password_placeholder') }}"
                           value="{{ old('smtp_password', $config->get('smtp_password')) }}"/>
                    @error('smtp_password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="smtpPort" class="form-label">{{ __('smtp_port') }}</label>
                    <input type="number" class="form-control" id="smtpPort" name="smtp_port"
                           placeholder="{{ __('smtp_port_placeholder') }}"
                           value="{{ old('smtp_port', $config->get('smtp_port')) }}"/>
                    @error('smtp_port')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="securityType" class="form-label">{{ __('security_type') }}</label>
                    <select class="form-select select2" id="securityType" name="security_type">
                        <option value="tls"
                                @if($config->get('security_type') === "tls") selected @endif>{{ __('tls') }}</option>
                        <option value="ssl"
                                @if($config->get('security_type') === "ssl") selected @endif>{{ __('ssl') }}</option>
                        <option value="none"
                                @if($config->get('security_type') === "none") selected @endif>{{ __('none') }}</option>
                    </select>
                    @error('security_type')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="testEmail" class="form-label">{{ __('test_email') }}</label>
                    <input type="email" class="form-control" id="testEmail" name="test_email"
                           placeholder="{{ __('test_email_placeholder') }}"
                           value="{{ old('test_email', $config->get('test_email')) }}"/>
                    @error('test_email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if($user->checkHasOrganizationPermission(BaseEnum::CONFIG['UPDATE'], session('organization_id')))
                    <button type="submit" class="btn btn-primary">{{ __('save_configurations') }}</button>
                @endif
            </form>
        </div>
    </div>
</div>
