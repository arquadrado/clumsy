@include($view->resolve('page-header'))

@include($view->resolve('filter'))
@include($view->resolve('filter-toggles'))
@include($view->resolve($innerView, $resource))
@include($view->resolve('pagination'))