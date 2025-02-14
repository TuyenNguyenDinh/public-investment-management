@php use App\Enums\BaseEnum; $user=auth()->user(); @endphp
<div class="tab-pane fade show active" id="common" role="tabpanel" aria-labelledby="common-tab">
    <div class="card mb-6">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap row-gap-2">
            <div class="card-title m-0">
                <h5 class="m-0">{{ __('general_configurations') }}</h5>
                <p class="my-0 card-subtitle">{{ __('manage_configurations') }}</p>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('app-configs-update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Favicon Configuration -->
                <div class="mb-4">
                    <label for="favicon" class="form-label">{{ __('favicon_icon') }}</label>
                    <input type="file" id="favicon" name="favicon" class="form-control" accept="image/*">
                    <small class="form-text text-muted">{{ __('favicon_upload') }}</small>
                    @if($config->get('favicon'))
                        <p>{{ __('current_favicon') }} <img src="{{ asset($config->get('favicon_url')) }}" alt="Favicon" width="30"></p>
                    @endif
                    @error('favicon')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Logo Configuration -->
                <div class="mb-4">
                    <label for="logo" class="form-label">{{ __('logo') }}</label>
                    <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                    <small class="form-text text-muted">{{ __('logo_upload') }}</small>
                    @if($config->get('logo'))
                        <p>{{ __('current_logo') }} <img src="{{ asset($config->get('logo_url')) }}" alt="Logo" width="30"></p>
                    @endif
                    @error('logo')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- App Name Configuration -->
                <div class="mb-4">
                    <label for="app_name" class="form-label">{{ __('app_name') }}</label>
                    <input type="text" id="app_name" name="app_name" class="form-control" placeholder="{{ __('app_name_placeholder') }}"
                           value="{{ old('app_name', $config->get('app_name')) }}">
                    @error('app_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Language Configuration -->
                <div class="mb-4">
                    <label for="language" class="form-label">{{ __('language') }}</label>
                    <select id="language" name="language" class="form-select select2">
                        <option value="vn" @if($config->get('language') === "vn") selected @endif>{{ __('vietnamese') }}</option>
                        <option value="en" @if($config->get('language') === "en") selected @endif>{{ __('english') }}</option>
                    </select>
                    @error('language')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date Format Configuration -->
                <div class="mb-4">
                    <label for="date_format" class="form-label">{{ __('date_format') }}</label>
                    <select id="date_format" name="date_format" class="form-select select2">
                        <option value="Y-m-d" @if($config->get('date_format') === 'Y-m-d') selected @endif>{{ __('date_format_Y-m-d') }}</option>
                        <option value="d/m/Y" @if($config->get('date_format') === 'd/m/Y') selected @endif>{{ __('date_format_d_m_Y') }}</option>
                        <option value="m/d/Y" @if($config->get('date_format') === 'm/d/Y') selected @endif>{{ __('date_format_m_d_Y') }}</option>
                        <option value="d-m-Y" @if($config->get('date_format') === 'd-m-Y') selected @endif>{{ __('date_format_d_M_Y') }}</option>
                        <option value="d/m/y" @if($config->get('date_format') === 'd/m/y') selected @endif>{{ __('date_format_d_m_y') }}</option>
                    </select>
                    @error('date_format')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Time Format Configuration -->
                <div class="mb-4">
                    <label for="time_format" class="form-label">{{ __('time_format') }}</label>
                    <select id="time_format" name="time_format" class="form-select select2">
                        <option value="H:i" @if($config->get('time_format') === 'H:i') selected @endif>{{ __('time_format_H_i') }}</option>
                        <option value="h:i A" @if($config->get('time_format') === 'h:i A') selected @endif>{{ __('time_format_h_i_A') }}</option>
                        <option value="H:i:s" @if($config->get('time_format') === 'H:i:s') selected @endif>{{ __('time_format_H_i_s') }}</option>
                        <option value="h:i:s A" @if($config->get('time_format') === 'h:i:s A') selected @endif>{{ __('time_format_h_i_s_A') }}</option>
                    </select>
                    @error('time_format')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New post date -->
                <div class="mb-4">
                    <label for="new_post_date" class="form-label">{{ __('new_post_date') }}</label>
                    <input type="number" id="new_post_date" name="new_post_date" class="form-control" placeholder="{{ __('new_post_date') }}"
                           value="{{ old('new_post_date', $config->get('new_post_date')) }}">
                    @error('new_post_date')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Thumbnail post width size -->
                <div class="mb-4">
                    <label for="thumbnail_post_width_size"
                           class="form-label">{{ __('thumbnail_post_width_size') }}</label>
                    <input type="number" id="thumbnail_post_width_size" name="thumbnail_post_width_size"
                           class="form-control" placeholder="{{ __('thumbnail_post_width_size') }}"
                           value="{{ old('thumbnail_post_width_size', $config->get('thumbnail_post_width_size')) }}"
                           oninput="this.value = Math.round(this.value);">
                    @error('thumbnail_post_width_size')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Thumbnail post height size -->
                <div class="mb-4">
                    <label for="thumbnail_post_height_size"
                           class="form-label">{{ __('thumbnail_post_height_size') }}</label>
                    <input type="number" id="thumbnail_post_height_size" name="thumbnail_post_height_size"
                           class="form-control" placeholder="{{ __('thumbnail_post_height_size') }}"
                           value="{{ old('thumbnail_post_height_size', $config->get('thumbnail_post_height_size')) }}"
                           oninput="this.value = Math.round(this.value);">
                    @error('thumbnail_post_height_size')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                @if($user->hasOrganizationPermission(BaseEnum::CONFIG['UPDATE'], session('organization_id')))
                    <button type="submit" class="btn btn-label-primary">{{ __('save_configurations') }}</button>
                @endif
            </form>
        </div>
    </div>
</div>
