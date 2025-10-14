
<li class="{{ Request::is('leca/repor-management-attachments') ? 'active' : '' }}">
    <a href="{{ url('leca/repor-management-attachments?report_id='.$_GET["report_id"]) }}"><i class="fa fa-paperclip"></i><span>@lang('Adjuntos de informe')</span></a>
</li>
