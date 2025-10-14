<!-- resources/views/pdf/show.blade.php -->

@extends('layouts.default')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Ver PDF</div>

                <div class="card-body">
                    <!-- Visualización del PDF en un visor -->
                    <div id="pdf-viewer">
                        <iframe src="{{ asset('storage') . '/' . $document->url_document_digital }}" width="100%" height="500"></iframe>
                    </div>

                    <!-- Modal para editar metadatos -->
                    <div class="modal fade" id="metadataModal" tabindex="-1" role="dialog" aria-labelledby="metadataModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="metadataModalLabel">Editar Metadatos del PDF</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Formulario para editar metadatos -->
                                    <form action="{{ route('update-metadata', $document->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-group">
                                            <label for="title">Item de información:</label>
                                            <input type="text" name="title" class="form-control" value="{{ $metadata->title ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="author">Número de página:</label>
                                            <input type="text" name="author" class="form-control" value="{{ $metadata->author ?? '' }}">
                                        </div>
                                        <!-- Agregar más campos para otros metadatos si es necesario -->
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón para abrir el modal de edición de metadatos -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#metadataModal">
                        Agregar Metadatos al documento
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
