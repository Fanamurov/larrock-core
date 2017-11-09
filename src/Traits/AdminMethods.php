<?php

namespace Larrock\Core\Traits;

trait AdminMethods{
    use AdminMethodsIndex, AdminMethodsCreate,
        AdminMethodsEdit, AdminMethodsStore, AdminMethodsDestroy, AdminMethodsUpdate;
}