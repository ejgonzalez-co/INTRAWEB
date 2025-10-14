@php
$ultima_configuracion = DB::table('configuration_general')->latest()->first();
$ultima_version = DB::table('intraweb_version_update')->latest()->first();
$historial_versiones = DB::table('intraweb_version_update')->latest()->get();
@endphp
<!-- begin #footer -->
<div id="footer" class="footer" style="{{ Request::is('*modules') || Request::is('*components*') ? 'margin-left: 5px; margin-right: 5px; bottom: 0px; position: absolute; width: 99%; background-color: '.$ultima_configuracion->color_barra.'; color: white; padding-left: 10px;' : 'bottom: -2%; position: absolute; width: 100%; max-width: -webkit-fill-available;' }}">
	&copy; {{ date("Y") }} <a href="https://web.whatsapp.com/send/?phone=573243018787" target="_blank">Intraweb</a> - Todos los derechos reservados. <a href="#" data-toggle="modal" data-target="#terminos_condiciones_modal">Términos, Condiciones de uso y Aviso Legal.</a><a> Versión: </a><a href="#" data-toggle="modal" data-target="#modal_lanzamiento">{{$ultima_version->numero_version}}</a>
</div>
<!-- end #footer -->

<!-- Modal -->
<div class="modal fade" id="terminos_condiciones_modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h3 class="modal-title">TÉRMINOS Y CONDICIONES DE USO</h3>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">

			<p>Los presentes términos y condiciones de uso (en adelante, "Términos y Condiciones") regulan el acceso y el uso de la Intraweb de Seven Soluciones Informáticas S.A.S (en adelante, "Intraweb"), así como la creación y administración de la cuenta de usuario en la Intraweb (en adelante, "Cuenta").</p>

			<h4>Titularidad</h4>

			<p>La titularidad de la Intraweb corresponde a Seven Soluciones Informáticas S.A.S, con domicilio social en Armenia.</p>

			<h4>Acceso a la Intraweb</h4>

			<p>El acceso a la Intraweb es gratuito. Sin embargo, para poder acceder a la Cuenta, el usuario deberá registrarse en la Intraweb y proporcionar sus datos personales.</p>

			<h4>Uso de la Intraweb</h4>

			<p>El usuario podrá utilizar la Intraweb para acceder a información y servicios relacionados con la actividad de Seven Soluciones Informáticas S.A.S. En particular, el usuario podrá:</p>

			<ul>
			<li>Consultar información sobre la empresa, sus productos y servicios.</li>
			<li>Acceder a documentos y archivos relacionados con su trabajo.</li>
			<li>Utilizar los servicios de comunicación interna de la empresa.</li>
			</ul>

			<h4>Cuenta de usuario</h4>

			<p>La Cuenta es un espacio personal en la Intraweb donde el usuario podrá almacenar información y acceder a servicios exclusivos. Para crear una Cuenta, el usuario deberá proporcionar sus datos personales y una contraseña.</p>

			<h4>Derechos de propiedad intelectual</h4>

			<p>Los contenidos de la Intraweb, incluidos los textos, imágenes, vídeos, audios y software, están protegidos por derechos de propiedad intelectual. El usuario deberá respetar estos derechos y no podrá utilizar los contenidos de la Intraweb de forma que pueda vulnerarlos.</p>

			<h4>Responsabilidades</h4>

			<p>Seven Soluciones Informáticas S.A.S no se responsabiliza de los daños y perjuicios que el usuario pueda sufrir como consecuencia del uso de la Intraweb. El usuario es el único responsable de los datos personales y de la información que proporcione a la Intraweb.</p>

			<h4>Modificaciones de los Términos y Condiciones</h4>

			<p>Seven Soluciones Informáticas S.A.S se reserva el derecho de modificar los Términos y Condiciones en cualquier momento. Las modificaciones entrarán en vigor a partir de su publicación en la Intraweb.</p>

			<h4>Ley aplicable y jurisdicción</h4>

			<p>Los presentes Términos y Condiciones se rigen por la ley española. Cualquier controversia que surja en relación con los mismos será resuelta por los tribunales competentes de la ciudad de Armenia.</p>

			<br />
			<h3>Aviso Legal</h3>

			<p>En cumplimiento de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y Comercio Electrónico (LSSI-CE), le informamos que el titular de esta web es Seven Soluciones Informáticas S.A.S, con domicilio en Armenia.</p>

			<h4>Información general</h4>

			<p>La finalidad de esta web es ofrecer información sobre los productos y servicios de Seven Soluciones Informáticas S.A.S.</p>

			<h4>Propiedad intelectual e industrial</h4>

			<p>Todos los derechos de propiedad intelectual e industrial de esta web, incluyendo el diseño, los textos, las imágenes, las fotografías, los sonidos, los vídeos, los software, los contenidos y los códigos, pertenecen a Seven Soluciones Informáticas S.A.S o a sus licenciantes.</p>

			<p>Queda totalmente prohibida la reproducción, distribución, comunicación pública, transformación, puesta a disposición del público y, en general, cualquier otra forma de explotación, por cualquier procedimiento, de todo o parte de los contenidos de esta web, sin la autorización previa y expresa de Seven Soluciones Informáticas S.A.S.</p>

			<h4>Responsabilidades</h4>

			<p>Seven Soluciones Informáticas S.A.S no se responsabiliza de los daños y perjuicios que pudieran derivarse de la utilización de esta web, ni de los daños y perjuicios derivados de la utilización de los servicios de terceros a los que se pudiera acceder a través de esta web.</p>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		</div>
	</div>
	</div>
</div>

<!-- Modal de lanzamientos -->
<div class="modal fade" id="modal_lanzamiento" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h3 class="modal-title">Intraweb {{$ultima_version->numero_version}}</h3>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<h5>Descripción del lanzamiento</h5>
			<pre style="white-space: pre-wrap;">{{$ultima_version->descripcion}}</pre>
			<hr>
			<p>
				<a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
					Historial de lanzamientos
				</a>
			</p>
			<div class="collapse" id="collapseExample">
				<div class="card card-body">
					<div>
						<table class="table text-center mt-2" border="1">
							<thead>
								<tr class="font-weight-bold" style="background-color: #00bcd47d">
									<td>Versión</td>
									<td>Descripción</td>
									<td>Fecha de actualización</td>
								</tr>
							</thead>
							<tbody>
								@foreach($historial_versiones as $historial)
								<tr>
									<td>{{ $historial->numero_version }}</td>
									<td><pre style="white-space: pre-wrap; text-align: left;">{{ $historial->descripcion }}</pre></td>
									<td>{{ $historial->created_at }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		</div>
	</div>
	</div>
</div>
<img ref="image" style="display: none;" src="/assets/img/loadingintraweb.gif" alt="Imagen">
