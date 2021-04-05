<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\ContactFormRequest;
use Faker\Factory;
use Tests\TestCase;

class ContactFormRequestTest extends TestCase
{
    /**
     * @var mixed
     */
    private $validator;

    /**
     * @var array
     */
    private $rules;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = app()->get('validator');
        $this->rules = (new ContactFormRequest())->rules();
    }

    public function validationProvider(): array
    {
        $faker = Factory::create(Factory::DEFAULT_LOCALE);

        return [
            'request_should_fail_when_no_name_is_provided' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->safeEmail,
                ]
            ],
            'request_should_fail_when_name_has_more_than_255_characters' => [
                'passed' => false,
                'data' => [
                    'name' => $faker->paragraph(10),
                    'email' => $faker->safeEmail

                ]
            ],
            'request_should_fail_when_no_email_is_provided' => [
                'passed' => false,
                'data' => [
                    'name' => $faker->word()
                ]
            ],
            'request_should_fail_when_wrong_email_is_provided' => [
                'passed' => false,
                'data' => [
                    'name' => $faker->word(),
                    'email' => $faker->word()
                ]
            ],
            'request_should_pass_when_data_is_provided' => [
                'passed' => true,
                'data' => [
                    'name' => $faker->word(),
                    'email' => $faker->safeEmail
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected(bool $shouldPass, array $mockedRequestData)
    {
        $this->assertEquals(
            $shouldPass,
            $this->validate($mockedRequestData)
        );
    }

    /**
     * @param $mockedRequestData
     * @return mixed
     */
    protected function validate($mockedRequestData)
    {
        return $this->validator
            ->make($mockedRequestData, $this->rules)
            ->passes();
    }
}
