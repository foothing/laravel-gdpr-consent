# Laravel GDPR Consent

Light-weight Laravel 5 package
for user's consents and data processing records.

## Install

Via composer

`composer require foothing/laravel-gdpr-consent`

Add service provider in `config/app.php`
```php
'providers' => [
	// omitted

	Foothing\Laravel\Consent\ConsentServiceProvider::class
]
```

Add alias in `config/app.php` if you want to use the facade
```php
'aliases' => [
	// omitted

	'Consent' => Foothing\Laravel\Consent\Facades\Consent::class
]
```

Publish config and migrations

`php artisan vendor:publish --tag=config`

`php artisan vendor:publish --tag=migrations`

Configure as needed, then run the migration
`php artisan migrate`

Setup the treatments table
`php artisan consent`

## Quick start

First you need to configure which `treatments` are required by your site.
You can do so in `config/consent.php`, like the following example:

```php
'treatments' => [
	[
		// A logical name for your treatment.
		'name' => 'gdpr.privacy',

		// You can specify which document
		// describes the treatment type
		// with a document version and url.
		// This part is optional.
		'documentVersion' => '1.0',
		'documentUrl' => env('PRIVACY_POLICY'),

		// Whether this treatment is active or not.
		// The reason why this flag is here is to
		// allow for progressive modifications, so you
		// can keep track of what the end user gave
		// consent to. So if you are upgrading or
		// changing the treatment the recommended
		// process is to deactivate the current one
		// then add a new record.
		'active' => true,

		// Set if this treatment is mandatory or optional.
		'required' => true,

		// A description text to be shown near a checkbox
		// or anywhere in your UI.
		'description' => 'gdpr.privacy.text',

		// UI weight, use this to choose what should be
		// listed first.
		'weight' => 0,
	],
	[
		'name' => 'gdpr.marketing',
		'documentVersion' => '1.0',
		'documentUrl' => env('PRIVACY_POLICY'),
		'active' => true,
		'required' => false,
		'description' => 'gdpr.marketing.text',
		'weight' => 1,
	],
]
```

Once you are done with the configuration you can run `php artisan consent:setup` to update your treatments database.

Then make your `User` model implement the
`ConsentSubject` Contract and use the `HasConsents` trait.

```php
class User extends Model implements ConsentSubject
{
	// This trait will implement the relation to the consent table.
	use HasConsents;

	/**
     * Returns the subject id.
     *
     * @return mixed
     */
	public function getSubjectId()
	{
        return $this->id;
    }

}
```


Now you will be able to use the package API to register user's consent and
data processing events like in the following example.

### Display consent checkboxes in register page

```html
@foreach(Consent::treatments() as $treatment)
	<div class="checkbox">
		<label>
			<input name="consent_{{ $treatment->id }}" type="checkbox">
			{{ trans($treatment->description) }}
		</label>
	</div>
@endforeach
```

> Note that your `checkbox` name must match `consent_TREATMENTID`

### Validate checkboxes in your controller

```php
public function postRegister(Request $request)
{
	if (! $treatments = Consent::validate($request->all()) {
		// User didn't accept all the mandatory checkboxes.
		throw new \Exception("Consent is mandatory in order to proceed.");
	}
}
```

### Register the consent
```php
public function postRegister(Request $request)
{
	// omitted

	// This will register a record with the
	// user's consent along with an event log.
	Facade::grant($treatments, Auth::user());
}
```

### Show consent in a user's settings page
```html
<!-- Cycle through active treatments, just like the register page. -->
@foreach(Consent::treatments() as $treatment)
	<div class="checkbox">
		<label>
			<input {{ Consent::exists($user, $treatment) ? 'checked' : '' }} name="{{ $treatment->id }}" type="checkbox">
			{{ trans($treatment->description) }}
		</label>
	</div>
@endforeach
```

> Note that for the update handler the checkbox name should be the treatment id.

### Save user's consent changes
```php
public function postUpdateConsent(Request $request)
{
	// You should pass only the checkboxes as the first argument.
	Consent::update($request->except('_token'), Auth::user());

	// redirect as you like.
}
```

> Note that those are just examples to show the Consent API,
though i wouldn't recommend using the facade inside the views
and you can replicate the same feature manipulating data in
a controller, before the view is being rendered.

### Log the "right to erasure"
In your `delete user` controller or service:

```php
public function deleteIndex(Request $request)
{

	\DB::transaction(function() use($user)
	{
		// Delete the user first.
    	// i.e. $user->delete();

    	// This will remove consents and log the erasure request.
    	Consent::erase($user);
	});
}
```

### Events
It's not completely clear yet how anyone will approach logs
or data processing records, which should anyway be stored and
maintained. In order to provide a better flexibility an API
for events has been added aswell.

The events API is implicitly used within the only hardcoded
actions (*grant*, *revoke*, *erasure*) but you can use it
how you like to track meaningful data processing records.

```php
$event = new Event([
	// The subject id.
	'subject_id' => $user->id,

	// A string describing what has been done with subject's data.
	'action' => $action,

	// Optional, consent record id.
	'consent_id' => $consentId,
]);
Consent::log($event);

// Retrieving subject's logs
Consent::events($subject);
```

## "Pseudonymization" in event records
Since each event record stores subject's ip and the request payload,
those fields are encrypted with a 2-way algorithm.

Keep in mind that Laravel uses the `config/app.php` key to encrypt and decrypt, so if you change
that your data won't be decryptable anymore.

## Project status
This package is under active development and needs work,
but it got to the point where i had the features i need
at the moment.

What is needed yet
- api refactor
- test coverage to 100%
- test with every 5.x minor release
- travis integration
- style fixes

so feel free to drop a line if you'd like to contribute.

## License
MIT
