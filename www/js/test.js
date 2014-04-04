Benchmark.prototype.setup = function() {
    Klass1 = function() {}
    Klass1.prototype.foo = function() {
      log('foo');
    }
    Klass1.prototype.bar = function() {
      log('bar');
    }
    
    Klass2 = function() {
      var foo = function() {
            log('foo');
          },
          bar = function() {
            log('bar');
          };
    
      return {foo: foo, bar: bar}
    }
    
    
    var FooFunction = function() {
      log('foo');
    };
    var BarFunction = function() {
      log('bar');
    };
    
    Klass3 = function() {
      return {foo: FooFunction, bar: BarFunction}
    }
  };