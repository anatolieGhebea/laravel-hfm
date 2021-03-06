# draft 
#### UNSTABLE
# A simple Laravel packge to help DRY your code

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ghebby/laravel-hfm.svg?style=flat-square)](https://packagist.org/packages/ghebby/laravel-hfm)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/anatolieGhebea/laravel-hfm/run-tests?label=tests)](https://github.com/anatolieGhebea/laravel-hfm/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/ghebby/laravel-hfm.svg?style=flat-square)](https://packagist.org/packages/ghebby/laravel-hfm)


This packe aims to reduce verbose and repetiteve code in the application by generatinc it at runtime starting from a simple Array declaration in the Model.

>This is an opinionated approach, so make sure it suits your project guide lines before using it.

## Installation

1. This package publishes a config/laravel-hfm.php file. If you already have a file by that name, you must rename or remove it.
2. You can install the package via composer:

```bash
composer require ghebby/laravel-hfm
```

3. Optional: The service provider will automatically get registered. Or you may manually add the service provider in your config/app.php file:

```php
'providers' => [
    // ...
    Ghebby\LaravelHfm\LaravelHfmServiceProvider::class,
];
```

4. You should publish the config/laravel-hfm.php config file with:

```bash
php artisan vendor:publish --provider="Ghebby\LaravelHfm\LaravelHfmServiceProvider" --tag="config"
```

5. If you want to customize the views for the UI helper functions, then run  
```bash
php artisan vendor:publish --provider="Ghebby\LaravelHfm\LaravelHfmServiceProvider" --tag="views"
```
to bublish them in ``resources/views/vendor/laravel-hfm/``



## Usage

Defining a Field Map on the Model may have a lot of benifits in terms of code duplication, making it easier and faster to develop trivil parts of the application. 

**Let's see a short example.**
Model name: _company_
Model fileds: 
- id
- name
- fiscal_code
- email
- phone
- description
- address
- city
- zip
- country

#### Tipical approach 

In a tipical situation in the model `Company.php` we will have something like this
```php
namespace App\Models;

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = ['name', 'fiscal_code', '...'];
    // OR
    protected $guarded = ['id'];

}

```

On the CompanyController we will have something like

```php
namespace App\Http\Controllers;

class CompanyController extends Controller
{

    // [...]

    public function create(Request $request)
    {
        // 
        return view('company.create');
    }

    public function store(Request $request)
    {
        // before persisting the data n DB you must validate it.
        // thus writing validation rules is a mandatory action
        // and it might look like this.
        $validaData = $request->validate(
            [
                'name' => 'string|max:255|required',
                'fiscal_code' => 'string|max:255|required',
                'email' => 'email|max:255|required',
                'phone' => 'string|max:255|nullable',
                //....
            ]
        ); 

        Company::create($validaData);

        return redirect()->route('company.index');
    }

    public function edit(Request $request, $id)
    {
        //
        $company = Company::findOrFail($id);

        return view('company.edit')->with('company', $company);
    }

    public function update(Request $request, $id)
    {
        // before persisting the data n DB you must validate it.
        // thus writing validation rules is a mandatory action
        // and it might look like this.
        $validaData = $request->validate(
            [
                'name' => 'string|max:255|required',
                'fiscal_code' => 'string|max:255|required',
                'email' => 'email|max:255|required',
                'phone' => 'string|max:255|nullable',
                //....
            ]
        ); 

        $company = Company::findOrFail($id);
        $company->update($validaData);

        return redirect()->route('company.index');
    }

    // [...]
}
```

On the view side a tipical situation may be
```php
//[....]
<form method="POST" action="{ route('company.store') }" >
    @csrf
    <div>
        <div>
            <label for="name">Name</label>
            <input name="name" type="text" class="" required>
        </div>
        <div>
            <label for="fiscal_code">Fiscal Code</label>
            <input name="fiscal_code" type="text" class="" required>
        </div>
        // [...]
        <div>
            <label for="country">Country</label>
            <input name="country" type="text" class="">
        </div>
    </div>
    <button type="submit">Create</button>
</form>
//[....]
```

I hope it's easy to see the problem. If for some reason a constraint is change we will need to fix the code in a few places:
- in controller@store -> validationRules
- in controller@update -> validationRules
- in view.create -> fields
- in view.edit -> fields

#### Using this package

The package provides:
- a Contract Interface to be implemented by the model
- a HfmTrait that contains the most used methods for manipolating the Field Map
- a HfmConst and HfmHelper which gets autoloaded on app boot, in order to define the cosnstants
- a few view helper functions which have the ability to generate a UI given a FieldMap

Let's how the code changes when sing this package:

Model `Company.php`
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Ghebby\LaravelHfm\Traits\FieldsMapTrait;
use Ghebby\LaravelHfm\Contracts\FieldsMapContract;

class Company extends Model implements FieldsMapContract
{
    use FieldsMapTrait;

    protected $table = 'companies';

    protected $fillable = ['name', 'fiscal_code', '...'];
    // OR
    protected $guarded = ['id'];

    /**
     * Returns the DB fields for the model. 
     * This map is used to automatically the create/update form
     */
    public static function getFieldsMap() {

        $fields = [
            'id' => [ FLD_LABEL => 'Id', FLD_UI_CMP => CMP_TEXT,  FLD_DATA_TYPE => DT_INT, FLD_LENGTH => 11 , FLD_FLT_COND => 'LIKE', FLD_PRIMARY => true,  FLD_REQUIRED => false ] ,
            'name' => [ FLD_LABEL => 'Company name', FLD_UI_CMP => CMP_TEXT,  FLD_DATA_TYPE => DT_TEXT, FLD_LENGTH => 255 , FLD_FLT_COND => 'LIKE', FLD_REQUIRED => true ] ,
            'fiscal_code' => [ FLD_LABEL => 'Fiscal Code', FLD_UI_CMP => CMP_TEXT,  FLD_DATA_TYPE => DT_TEXT, FLD_LENGTH => 255 , FLD_FLT_COND => 'LIKE', FLD_REQUIRED => true ] ,
            'email' => [ FLD_LABEL => 'E-mail', FLD_UI_CMP => CMP_TEXT,  FLD_DATA_TYPE => DT_TEXT, FLD_LENGTH => 255 , FLD_FLT_COND => 'LIKE', FLD_REQUIRED => true ] ,
            'phone' => [ FLD_LABEL => 'Phone', FLD_UI_CMP => CMP_TEXT,  FLD_DATA_TYPE => DT_TEXT, FLD_LENGTH => 50 , FLD_FLT_COND => 'LIKE', FLD_REQUIRED => false ],
            'description'=> [ FLD_LABEL => 'Description',FLD_UI_CMP => CMP_TEXT_AREA,  FLD_DATA_TYPE => DT_TEXT_AREA, FLD_LENGTH => -1, FLD_FLT_COND => 'LIKE', FLD_REQUIRED => false ] ,
            'address' => [ FLD_LABEL => 'Address', FLD_UI_CMP => CMP_TEXT,  FLD_DATA_TYPE => DT_TEXT, FLD_LENGTH => 255 , FLD_FLT_COND => 'LIKE', FLD_REQUIRED => true ],
            'city' => [ FLD_LABEL => 'City', FLD_UI_CMP => CMP_TEXT,  FLD_DATA_TYPE => DT_TEXT, FLD_LENGTH => 100 , FLD_FLT_COND => 'LIKE', FLD_REQUIRED => true ],
            'zip' => [ FLD_LABEL => 'zip', FLD_UI_CMP => CMP_TEXT,  FLD_DATA_TYPE => DT_TEXT, FLD_LENGTH => 10 , FLD_FLT_COND => '=', FLD_REQUIRED => true ],
            'country' => [ FLD_LABEL => 'Country', FLD_UI_CMP => CMP_TEXT,  FLD_DATA_TYPE => DT_TEXT, FLD_LENGTH => 100 , FLD_FLT_COND => 'LIKE', FLD_REQUIRED => true ],
        ];

        return $fields;
    }

}
```
On controller 
```php
namespace App\Http\Controllers;

class CompanyController extends Controller
{

    // [...]

    public function create(Request $request)
    {
        // 
        $fields = Company::getFieldsMap();
        
        if( isset($fields['id']) ){
            // when creating a new entry, id field in required normaly.
            unset($fields['id']);
        }

        return view('company.create')->with('fields', $fields);
    }

    public function store(Request $request)
    {

        $rules = Company::getDefaultValidationRules();
        // $rules is an array of validation rules for each field in the field map, 
        // which nakes it very easy to override or integrate rules, example
        // $rules['fiscal_code'][] = 'min:10'; // addd a constraint for min number of char
        // unset($rules['email']); // this will prevent the validator method to set the key value pair for 'email' in $validaData

        $validaData = $request->validate($rules); 

        Company::create($validaData);

        return redirect()->route('company.index');
    }

    public function edit(Request $request, $id)
    {
        //
        $company = Company::findOrFail($id);
        $fields = Company::getFieldsMap();

        return view('company.edit')->with('company', $company)->with('fields', $fields);
    }

    public function update(Request $request, $id)
    {

        $rules = Company::getDefaultValidationRules();
        $validaData = $request->validate($rules);

        $company = Company::findOrFail($id);
        $company->update($validaData);

        return redirect()->route('company.index');
    }

    // [...]
}
```

On the view side
```php
//[....]
<form method="POST" action="{ route('company.store') }" >
    @csrf
    <div>
        @if( count($fileds) > 0 )
            // if you have published and customized the UI helper functions, use this
            @include('vendor.anatolieghebea.laravel-hfm.helpers._standardForm', ['stdFields' => $fileds])

            // if using the package default helper functions
            @include('laravel-hfm::helpers._standardForm', ['stdFields' => $fileds])
        @endif
    </div>
    <button type="submit">Create</button>
</form>
//[....]
```
```php
//[....]
<form method="POST" action="{ route('company.edit', $company->id) }" >
    @csrf
    <div>
        @if( count($fileds) > 0 )
            // if you have published and customized the UI helper functions, use this
            @include('vendor.anatolieghebea.laravel-hfm.helpers._standardForm', ['stdFields' => $fileds, 'mainModel' => $company, 'op' => 'edit'])

            // if using the package default helper functions
            @include('laravel-hfm::helpers._standardForm', ['stdFields' => $fileds, 'mainModel' => $company, 'op' => 'edit'])
        @endif
    </div>
    <button type="submit">Save</button>
</form>
//[....]
```

With this approach we centralized the source of truth in the application, if we change the label for the filed `name` in Company.php it will propagete to all the views that relies on the `_standardForm` helper function. 

If in the Field Map on the filed `fiscal_code` the attribute `required` is set to FALSE, the change will immediately affect the store and update methods in Controller and also the required attribute on the input field. 

### Adding removing fields

Adding or removing a field to an Model entity, is just the mater of adding o removing a line in the ``getFieldMap()`` of the model.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Anatolie Ghebea](https://github.com/anatolieGhebea)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
