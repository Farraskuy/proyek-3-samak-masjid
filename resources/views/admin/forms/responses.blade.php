@extends('admin.layout')

@section('title', 'Respon Formulir')

@section('content')
    <div class="p-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Respon: {{ $form->title }}</h4>
                <p class="text-muted mb-0">Total {{ $responses->count() }} tanggapan diterima</p>
            </div>
            <a href="{{ route('admin.forms.index') }}" class="btn btn-light border bg-white text-dark fw-medium">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        {{-- Tabs --}}
        {{-- Tabs (Pill Style) --}}
        <div class="d-flex gap-2 mb-4 p-2 rounded-pill" style="background-color: rgba(0,0,0,0.05); width: fit-content;">
            <button class="btn btn-sm rounded-pill px-4 fw-semibold active" id="summary-tab" data-bs-toggle="pill"
                data-bs-target="#summary" type="button" role="tab">
                Ringkasan
            </button>
            <button class="btn btn-sm rounded-pill px-4 fw-semibold" id="individual-tab" data-bs-toggle="pill"
                data-bs-target="#individual" type="button" role="tab">
                Daftar Responden
            </button>
        </div>

        <div class="tab-content" id="responseTabsContent">
            {{-- Tab 1: Ringkasan --}}
            <div class="tab-pane fade show active" id="summary" role="tabpanel">
                <div class="row g-4">
                    @foreach ($form->fields as $field)
                        @if (in_array($field->type, ['header']))
                            @continue
                        @endif

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold mb-3">{{ $field->label }}</h6>

                                    @php
                                        // Collect all answers for this field
                                        $answers = $responses->flatMap(function ($r) use ($field) {
                                            return $r->items->where('field_name', $field->name)->pluck('value');
                                        });
                                    @endphp

                                    @if ($answers->isEmpty())
                                        <p class="text-muted small fst-italic">Belum ada jawaban.</p>
                                    @else
                                        <div class="bg-light rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                            <ul class="list-unstyled mb-0 small">
                                                @foreach ($answers as $ans)
                                                    <li class="mb-2 border-bottom pb-2 last-no-border">
                                                        {{ is_array(json_decode($ans, true)) ? implode(', ', json_decode($ans, true)) : $ans }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="mt-2 text-end">
                                            <span class="badge bg-secondary">{{ $answers->count() }} Jawaban</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab 2: Individual --}}
            <div class="tab-pane fade" id="individual" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3 text-secondary small fw-bold text-uppercase">ID</th>
                                        <th class="px-4 py-3 text-secondary small fw-bold text-uppercase">Waktu Submit</th>
                                        <th class="px-4 py-3 text-secondary small fw-bold text-uppercase">IP Address</th>
                                        <th class="px-4 py-3 text-secondary small fw-bold text-uppercase text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($responses as $r)
                                        <tr>
                                            <td class="px-4 fw-medium">#{{ $r->id }}</td>
                                            <td class="px-4 text-muted">{{ $r->created_at->format('d M Y, H:i') }}</td>
                                            <td class="px-4 text-muted">{{ $r->ip_address }}</td>
                                            <td class="px-4 text-end">
                                                <a href="{{ route('admin.forms.responses.show', [$form->id, $r->id]) }}"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    Detail <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('admin.forms.responses.delete', [$form->id, $r->id]) }}"
                                                    class="d-inline-block ms-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger rounded-circle"
                                                        onclick="return confirm('Hapus respon ini?')" title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3 text-light-emphasis"></i>
                                                <p class="mb-0">Belum ada respon yang masuk.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
