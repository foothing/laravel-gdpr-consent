<?php namespace tests\Foothing\Laravel\Consent;

use Foothing\Laravel\Consent\ConsentApi;
use Foothing\Laravel\Consent\Models\Treatment;
use Foothing\Laravel\Consent\Repositories\ConsentRepository;
use Foothing\Laravel\Consent\Models\Consent;
use Mockery\MockInterface;
use tests\Foothing\Laravel\Consent\Mocks\User;

class ConsentApiTest extends DatabaseTest {

    /**
     * @var MockInterface
     */
    protected $repository;

    /**
     * @var ConsentApi
     */
    protected $api;

    public function setUp()
    {
        parent::setUp();

        $this->repository = \Mockery::mock(ConsentRepository::class);

        $this->api = new ConsentApi($this->repository);
    }

    /**
     * One consent and one event is created for each treatment.
     *
     * @test
     */
    public function it_should_create_new_consent_and_log()
    {
        $treatments = Treatment::all();

        $consents = $this->api->grant($treatments->all(), $user = new User());

        $this->assertEquals($treatments->count(), count($consents));
        $this->assertEquals($treatments->count(), $this->api->events($user)->count());
    }

    /**
     * @test
     */
    public function it_should_not_revoke_anything()
    {
        $treatments = Treatment::all();

        $consents = $this->api->revoke($treatments[0]->id, $user = new User());

        $this->assertTrue($consents);
    }

    /**
     * @test
     */
    public function it_should_revoke_and_log_with_treatment_reference()
    {
        $treatments = Treatment::all();

        $this->api->grant($treatments->all(), $user = new User());

        $event = $this->api->revoke($treatments[0]->id, $user);

        $this->assertEquals("consent.revoke", $event->action);
        $this->assertEquals($user->getSubjectid(), $event->subject_id);
        $this->assertEquals($treatments[0]->id, $event->treatment_id);
    }

    public function tearDown()
    {
        Consent::query()->delete();
    }
}
