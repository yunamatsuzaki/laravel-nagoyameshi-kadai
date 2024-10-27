@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('/js/preview.js') }}"></script>
@endpush

@section('content')
    <div class="col container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9">
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.restaurants.index') }}">店舗一覧</a></li>
                        <li class="breadcrumb-item active" aria-current="page">店舗登録</li>
                    </ol>
                </nav>

                <h1 class="mb-4 text-center">店舗登録</h1>

                <hr class="mb-4">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.restaurants.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row mb-3">
                        <label for="name" class="col-md-5 col-form-label text-md-left fw-bold">店舗名</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="image" class="col-md-5 col-form-label text-md-left fw-bold">店舗画像</label>

                        <div class="col-md-7">
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                    </div>

                    <!-- 選択された画像の表示場所 -->
                    <div class="row" id="imagePreview"></div>

                    <div class="form-group row mb-3">
                        <label for="description" class="col-md-5 col-form-label text-md-left fw-bold">説明</label>

                        <div class="col-md-7">
                            <textarea class="form-control" id="description" name="description" cols="30" rows="5">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="lowest_price" class="col-md-5 col-form-label text-md-left fw-bold">最低価格</label>

                        <div class="col-md-7">
                            <select class="form-control form-select" id="lowest_price" name="lowest_price">
                                <option value="" hidden>選択してください</option>
                                @for ($i = 0; $i < 20; $i++)
                                    {{ $lowest_price = 500 + (500 * $i) }}
                                    @if ($lowest_price == old('lowest_price'))
                                        <option value="{{ $lowest_price }}" selected>{{ number_format($lowest_price) }}円</option>
                                    @else
                                        <option value="{{ $lowest_price }}">{{ number_format($lowest_price) }}円</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="highest_price" class="col-md-5 col-form-label text-md-left fw-bold">最高価格</label>

                        <div class="col-md-7">
                            <select class="form-control form-select" id="highest_price" name="highest_price">
                                <option value="" hidden>選択してください</option>
                                @for ($i = 0; $i < 20; $i++)
                                    {{ $highest_price = 500 + (500 * $i) }}
                                    @if ($highest_price == old('highest_price'))
                                        <option value="{{ $highest_price }}" selected>{{ number_format($highest_price) }}円</option>
                                    @else
                                        <option value="{{ $highest_price }}">{{ number_format($highest_price) }}円</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="postal_code" class="col-md-5 col-form-label text-md-left fw-bold">郵便番号</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="address" class="col-md-5 col-form-label text-md-left fw-bold">住所</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="opening_time" class="col-md-5 col-form-label text-md-left fw-bold">開店時間</label>

                        <div class="col-md-7">
                            <select class="form-control form-select" id="opening_time" name="opening_time">
                                <option value="" hidden>選択してください</option>
                                @for ($i = 0; $i < 48; $i++)
                                    {{ $opening_time = date('H:i', strtotime('00:00 +' . $i * 30 .' minute')) }}
                                    @if ($opening_time == old('opening_time'))
                                        <option value="{{ $opening_time }}" selected>{{ $opening_time }}</option>
                                    @else
                                        <option value="{{ $opening_time }}">{{ $opening_time }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="closing_time" class="col-md-5 col-form-label text-md-left fw-bold">閉店時間</label>

                        <div class="col-md-7">
                            <select class="form-control form-select" id="closing_time" name="closing_time">
                                <option value="" hidden>選択してください</option>
                                @for ($i = 0; $i < 48; $i++)
                                    {{ $closing_time = date('H:i', strtotime('00:00 +' . $i * 30 .' minute')) }}
                                    @if ($closing_time == old('closing_time'))
                                        <option value="{{ $closing_time }}" selected>{{ $closing_time }}</option>
                                    @else
                                        <option value="{{ $closing_time }}">{{ $closing_time }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-5 col-form-label text-md-left fw-bold">定休日</label>

                        <div class="col-md-7 d-flex flex-wrap">
                            @foreach ($regular_holidays as $index => $regular_holiday)
                                <div class="form-check d-flex align-items-center me-3">
                                    <input type="checkbox" class="form-check-input" id="regularHoliday{{ $index }}" name="regular_holiday_ids[]" value="{{ $regular_holiday->id }}">
                                    <label class="form-check-label" for="regularHoliday{{ $index }}"><span class="badge bg-secondary ms-1">{{ $regular_holiday->day }}</span></label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="seating_capacity" class="col-md-5 col-form-label text-md-left fw-bold">座席数</label>

                        <div class="col-md-7">
                            <input type="number" class="form-control" id="seating_capacity" name="seating_capacity" value="{{ old('seating_capacity') }}">
                        </div>
                    </div>

                    @for ($i = 0; $i < 3; $i++)
                        <div class="form-group row mb-3">
                            <label for="category{{ $i + 1 }}" class="col-md-5 col-form-label text-md-left fw-bold">カテゴリ{{ $i + 1 }}（3つまで選択可）</label>

                            <div class="col-md-7">
                                <select class="form-control form-select" id="category{{ $i + 1 }}" name="category_ids[]">
                                    <option value="">選択なし</option>
                                    @foreach ($categories as $category)
                                        @if ($category->id == old("category_ids.{$i}"))
                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                        @else
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endfor

                    <hr class="my-4">

                    <div class="form-group d-flex justify-content-center mb-4">
                        <button type="submit" class="btn text-white shadow-sm w-50 nagoyameshi-btn">登録</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection