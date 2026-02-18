<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Portfolio - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .item {
            margin-bottom: 15px;
        }
        .item-title {
            font-weight: bold;
            color: #34495e;
        }
        .item-subtitle {
            color: #7f8c8d;
            font-style: italic;
        }
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .skill-item {
            padding: 5px;
            background: #ecf0f1;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $user->name }}</h1>
        <p>{{ $portfolio->title ?? 'Professional Portfolio' }}</p>
        @if($user->email)
            <p>{{ $user->email }}</p>
        @endif
    </div>

    <!-- Summary -->
    @if($portfolio->summary)
    <div class="section">
        <div class="section-title">Resumen Profesional</div>
        <p>{{ $portfolio->summary }}</p>
    </div>
    @endif

    <!-- Work Experience -->
    @if(count($work_experience) > 0)
    <div class="section">
        <div class="section-title">Experiencia Laboral</div>
        @foreach($work_experience as $work)
        <div class="item">
            <div class="item-title">{{ $work->position }} - {{ $work->company }}</div>
            <div class="item-subtitle">
                {{ $work->start_date }} - {{ $work->end_date ?? 'Actualidad' }}
            </div>
            <p>{{ $work->description }}</p>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Education -->
    @if(count($education) > 0)
    <div class="section">
        <div class="section-title">Formación Académica</div>
        @foreach($education as $edu)
        <div class="item">
            <div class="item-title">{{ $edu->study_type }} - {{ $edu->area }}</div>
            <div class="item-subtitle">
                {{ $edu->institution }} ({{ $edu->start_date }} - {{ $edu->end_date }})
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Skills -->
    @if(count($skills) > 0)
    <div class="section">
        <div class="section-title">Habilidades Técnicas</div>
        <div class="skills-grid">
            @foreach($skills as $skill)
            <div class="skill-item">
                <strong>{{ $skill->name }}</strong><br>
                <small>{{ $skill->level }}</small>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Projects -->
    @if(count($projects) > 0)
    <div class="section">
        <div class="section-title">Proyectos Destacados</div>
        @foreach($projects as $project)
        <div class="item">
            <div class="item-title">{{ $project->title }}</div>
            <p>{{ $project->description }}</p>
            @if($project->url)
                <p><small>URL: {{ $project->url }}</small></p>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <div style="margin-top: 50px; text-align: center; color: #95a5a6; font-size: 10px;">
        Generado el {{ date('d/m/Y') }} desde ePortfolio
    </div>
</body>
</html>
