@if($options->auth)
{!!code()->tag('x-app-layout')!!}
@else
{!!code()->tag('x-guest-layout')!!}
@endif

<div class="container mx-auto my-8">
    <div class="grid md:grid-cols-3">
        <div class="mb-4 mx-4">
            <h1 class="mb-4 text-blue-500 text-3xl font-bold"> {{str($model->name)->title()}} Edit </h1>
            @@if ($errors->any())
            <ul class="list-disc list-inside text-sm text-red-500">
                @@foreach ($errors->all() as $error)
                <li class="">@{{ $error }}</li>
                @@endforeach
            </ul>
            @@endif
        </div>
        <div class="col-span-2 bg-white shadow rounded-lg overflow-auto">
            <form action="{{code()->doubleCurlyOpen()}}route('{{str($model->name)->slug()->plural()}}.update',['{{str($model->name)->snake()}}'=>${{str($model->name)->snake()}}->id]){{code()->doubleCurlyClose()}}" method="POST" novalidate>
                <div class="space-y-3 p-4">

                    @@csrf
                    @@method('PUT')
                    @foreach($model->relations as $rel)
                    @if($rel->type === 'BelongsTo')
                    <div class="">
                        <label class="block text-sm font-semibold text-gray-700" for="{{$rel->local_key}}">{{str($rel->name)->title()}}</label>
                        <input readonly type="text"
                            @@foreach((\{{$rel->model->complete_name}}::all() ?? [] ) as ${{$rel->name}})
                                @@if(${{str($model->name)->snake()}}->{{$rel->local_key}} == ${{$rel->name}}->id)
                                class="mt-1 block w-full sm:text-sm border-transparent focus:ring-transparent focus:outline-none focus:border-transparent rounded"
                                value="{{code()->doubleCurlyOpen()}}${{$rel->name}}->{{collect($rel->model->table->columns)->filter(fn($col,$key) => ($col->type == 'String') )->map(function($col){ return $col->name;})->first()}}{{code()->doubleCurlyClose()}}"
                                @@endif
                            @@endforeach
                        >

                    </div>
                    @endif
                    @endforeach

                    @foreach($model->table->columns as $column)
                    @if(!str($column->name)->matches('/id$/') && !str($column->name)->matches('/created_at$/') && !str($column->name)->matches('/updated_at$/') && !str($column->name)->matches('/deleted_at$/'))
                    <div class="">
                        <label class="block text-sm font-semibold text-gray-700" for="{{$column->name}}">{{str($column->name)->title()}}</label>
                        @if($column->type=='Text')
                        <textarea readonly name="{{$column->name}}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded {{$column->type}}">{{code()->doubleCurlyOpen()}}old('{{$column->name}}',${{str($model->name)->snake()}}->{{$column->name}}){{code()->doubleCurlyClose()}}</textarea>
                        @else
                        <input readonly name="{{$column->name}}" @if($column->type == 'String')
                        type="text"
                        maxlength="{{$column->length}}"
                        @elseif($column->type == 'DateTime')
                        type="date"
                        @elseif(str($column->type)->matches('/Int/'))
                        type="number"
                        @elseif(str($column->type)->matches('/Decimal|Float/'))
                        type="number"
                        step="0.01"
                        @endif
                        class="mt-1 block w-full sm:text-sm border-transparent focus:ring-transparent focus:outline-none focus:border-transparent rounded {{$column->type}}"
                        @if(!$column->nullable)
                        required="required"
                        @endif
                        value="{{code()->doubleCurlyOpen()}}old('{{$column->name}}',${{str($model->name)->snake()}}->{{$column->name}}){{code()->doubleCurlyClose()}}"
                        >
                        @endif
                        @@if($errors->has('{{$column->name}}'))
                        <p class="mt-0.5 text-sm text-red-500">{{code()->doubleCurlyOpen()}}$errors->first('{{$column->name}}'){{code()->doubleCurlyClose()}}</p>
                        @@endif
                    </div>
                    @endif
                    @endforeach
                </div>
                <div class="bg-gray-100 flex items-center justify-start px-4 py-5 space-x-3">
                    <a class="text-blue-500" href="@{{ url()->previous() }}">Back</a>
                </div>
            </form>
        </div>
    </div>
    @if($options->auth)
    {!!code()->tag('/x-app-layout')!!}
    @else
    {!!code()->tag('/x-guest-layout')!!}
    @endif