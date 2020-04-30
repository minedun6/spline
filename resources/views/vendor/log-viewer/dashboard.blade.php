@extends('log-viewer::_template.master')

@section('page-header')
    <h1>
        Log Viewer
        <small>By <a href="https://github.com/ARCANEDEV/LogViewer" target="_blank">ARCANEDEV</a></small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-body">
            <div class="row">
                @if (! count($percents))
                    <div class="col-md-12">
                        There are no statistics to show.
                    </div>
                @else
                    <div class="col-md-3">
                        <canvas id="stats-doughnut-chart"></canvas>
                    </div>
                    <div class="col-md-9">
                        <section class="box-body">
                            <div class="row">
                                @foreach($percents as $level => $item)
                                    <div class="col-md-4">
                                        <div class="info-box level level-{{ $level }} {{ $item['count'] === 0 ? 'level-empty' : '' }}">
                                    <span class="info-box-icon">
                                        {!! log_styler()->icon($level) !!}
                                    </span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">{{ $item['name'] }}</span>
                                                <span class="info-box-number">
                                            {{ $item['count'] }} entries - {!! $item['percent'] !!} %
                                        </span>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: {{ $item['percent'] }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                @endif
            </div>
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@endsection

@section('after-scripts-end')
    <script>
        $(function() {
            var data = {!! $reports !!};

            new Chart($('#stats-doughnut-chart')[0].getContext('2d'))
                .Doughnut(data, {
                    animationEasing : "easeOutQuart"
                });
        });
    </script>
@endsection