{!! view_render_event('admin.leads.view.attributes.before', ['lead' => $lead]) !!}

<div class="flex w-full flex-col gap-4 border-b border-gray-200 p-4 dark:border-gray-800">
    <x-admin::accordion class="select-none !border-none">
        <x-slot:header class="!p-0">
            <div class="flex w-full items-center justify-between gap-4 font-semibold dark:text-white">
                <h4>@lang('admin::app.leads.view.attributes.title')</h4>

                @if (bouncer()->hasPermission('leads.edit'))
                    <a
                        href="{{ route('admin.leads.edit', $lead->id) }}"
                        class="icon-edit rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                        target="_blank"
                    ></a>
                @endif
            </div>
        </x-slot:header>

        <x-slot:content class="mt-4 !px-0 !pb-0">
            {!! view_render_event('admin.leads.view.attributes.form_controls.before', ['lead' => $lead]) !!}

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, () => {})">
                    {!! view_render_event('admin.leads.view.attributes.form_controls.attributes.view.before', ['lead' => $lead]) !!}

                    @php
                        $customAttributes = app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                            'entity_type' => 'leads',
                            ['code', 'NOTIN', ['title', 'description', 'lead_pipeline_id', 'lead_pipeline_stage_id']]
                        ]);
                    @endphp

                    <div class="flex flex-col gap-1">
                        @foreach ($customAttributes as $attribute)
                            @if (view()->exists($typeView = 'admin::components.attributes.view.' . $attribute->type))
                                <div class="grid grid-cols-[1fr_2fr] items-center gap-1">
                                    <div class="label dark:text-white">{{ $attribute->name }}</div>

                                    <div class="font-medium dark:text-white">
                                        @include ($typeView, [
                                            'attribute' => $attribute,
                                            'value'     => $lead[$attribute->code] ?? null,
                                            'allowEdit' => true,
                                            'url'       => route('admin.leads.attributes.update', $lead->id),
                                        ])
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <!-- Custom Added Created At Field -->
                        <div class="grid grid-cols-[1fr_2fr] items-center gap-1">
                            <div class="label dark:text-white">Tanggal Dibuat</div>
                            <div class="font-medium dark:text-white">
                                {{ $lead->created_at->format('Y-m-d') }}
                            </div>
                        </div>
                    </div>

                    {!! view_render_event('admin.leads.view.attributes.form_controls.attributes.view.after', ['lead' => $lead]) !!}
                </form>
            </x-admin::form>

            {!! view_render_event('admin.leads.view.attributes.form_controls.after', ['lead' => $lead]) !!}
        </x-slot:content>
    </x-admin::accordion>
</div>

{!! view_render_event('admin.leads.view.attributes.before', ['lead' => $lead]) !!}
