<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FakerServiceProvider extends ServiceProvider {


    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('Faker', function($app) {
            $faker = \Faker\Factory::create();
            $newClass = new class($faker) extends \Faker\Provider\Base {

                public function vatNumberIt() {
                    return FakerServiceProvider::generateVatNumberIt();
                }

                public function fiscalNumberIt() {
                    return FakerServiceProvider::generateFiscalNumberIt();
                }
            };

            $faker->addProvider($newClass);
            return $faker;
        });
    }


    public static function generateVatNumberIt(): string {

        $faker = \Faker\Factory::create();
        $pi = $faker->numerify(str_repeat('#', 10));

        $s = 0;
        for($i = 0; $i <= 9; $i += 2) {
            $s += ord($pi[$i]) - ord('0');
        }

        for($i = 1; $i <= 9; $i += 2) {
            $c = 2 * (ord($pi[$i]) - ord('0'));
            if($c > 9) {
                $c -= 9;
            }
            $s += $c;
        }

        return $pi . (10 - $s % 10) % 10;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function generateFiscalNumberIt():string {

        $faker = \Faker\Factory::create();

        $code = $faker->regexify('[A-Z]{6}[0-9]{2}');//first and last name, birth year
        $code.= \Arr::random(['A', 'E', 'P', 'B', 'H', 'R', 'C', 'L', 'S', 'D', 'M', 'T']);//birth month
        $code.= str_pad(random_int(1,12),2, '0',STR_PAD_LEFT); //birth day
        $code.= $faker->regexify('[A-Z]{1}[0-9]{3}'); //birth city code

        $s = 0;
        for ($i = 1; $i <= 13; $i += 2) {
            $c = $code[$i];
            if (strcmp($c, '0') >= 0 && strcmp($c, '9') <= 0) {
                $s += ord($c) - ord('0');
            } else {
                $s += ord($c) - ord('A');
            }
        }

        for ($i = 0; $i <= 14; $i += 2) {
            $c = $code[$i];
            switch ($c) {
                case 'A':
                case '0':
                    ++$s;
                    break;
                case 'B':
                case '1':
                    $s += 0;
                    break;
                case 'C':
                case '2':
                    $s += 5;
                    break;
                case 'D':
                case '3':
                    $s += 7;
                    break;
                case 'E':
                case '4':
                    $s += 9;
                    break;
                case 'F':
                case '5':
                    $s += 13;
                    break;
                case 'G':
                case '6':
                    $s += 15;
                    break;
                case 'H':
                case '7':
                    $s += 17;
                    break;
                case 'I':
                case '8':
                    $s += 19;
                    break;
                case 'J':
                case '9':
                    $s += 21;
                    break;
                case 'K':
                    $s += 2;
                    break;
                case 'L':
                    $s += 4;
                    break;
                case 'M':
                    $s += 18;
                    break;
                case 'N':
                    $s += 20;
                    break;
                case 'O':
                    $s += 11;
                    break;
                case 'P':
                    $s += 3;
                    break;
                case 'Q':
                    $s += 6;
                    break;
                case 'R':
                    $s += 8;
                    break;
                case 'S':
                    $s += 12;
                    break;
                case 'T':
                    $s += 14;
                    break;
                case 'U':
                    $s += 16;
                    break;
                case 'V':
                    $s += 10;
                    break;
                case 'W':
                    $s += 22;
                    break;
                case 'X':
                    $s += 25;
                    break;
                case 'Y':
                    $s += 24;
                    break;
                case 'Z':
                    $s += 23;
                    break;
                default:
                    return false;
            }
        }

        return $code.chr($s % 26 + ord('A'));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        //
    }
}
