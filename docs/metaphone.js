;(()=>{
  let k="X",C="0";
  function H(e){
    let r="",t=0,o,i,f,a;
    function n(u){r+=u}
    function s(u){return e.charAt(t+u).toUpperCase()}
    function c(u){return function(){return s(u)}}
    
    if (e=String(e||""),!e) return"";
    
    for (i=c(1),f=c(0),a=c(-1);!v(f());) {
      if (!f()) return"";
      t++;
    }
    switch (f()) {
      case"A":
        i()==="E"?(n("E"),t+=2):(n("A"),t++);
        break;
      case"G":
      case"K":
      case"P":
        i()==="N"&&(n("N"),t+=2);
        break;
      case"W":
        i()==="R"?(n(i()),t+=2):i()==="H"?(n(f()),t+=2):p(i())&&(n("W"),t+=2);
        break;
      case"X":
        n("S"),t++;
        break;
      case"E":
      case"I":
      case"O":
      case"U":
        n(f()),t++;
        break;
      default: break
    }
    for (;f();) {
      if (o=1,!v(f())||f()===a()&&f()!=="C") {t+=o;continue}
      
      switch (f()) {
        case"B":
          a()!=="M"&&n("B");
          break;
        case"C":
          l(i())?i()==="I"&&s(2)==="A"?n(k):a()!=="S"&&n("S"):i()==="H"?(n(k),o++):n("K");
          break;
        case"D":
          i()==="G"&&l(s(2))?(n("J"),o++):n("T");
          break;
        case"G":
          i()==="H"?A(s(-3))||s(-4)==="H"||(n("F"),o++):i()==="N"?!v(s(2))||s(2)==="E"&&s(3)==="D"||n("K"):l(i())&&a()!=="G"?n("J"):n("K");
          break;
        case"H":
          p(i())&&!K(a())&&n("H");
          break;
        case"K":
          a()!=="C"&&n("K");
          break;
        case"P":
          i()==="H"?n("F"):n("P");
          break;
        case"Q":
          n("K");
          break;
        case"S":
          i()==="I"&&(s(2)==="O"||s(2)==="A")?n(k):i()==="H"?(n(k),o++):n("S");
          break;
        case"T":
          i()==="I"&&(s(2)==="O"||s(2)==="A")?n(k):i()==="H"?(n(C),o++):i()==="C"&&s(2)==="H"||n("T");
          break;
        case"V":
          n("F");
          break;
        case"W":
          p(i())&&n("W");
          break;
        case"X":
          n("KS");
          break;
        case"Y":
          p(i())&&n("Y");
          break;
        case"Z":
          n("S");
          break;
        case"F":
        case"J":
        case"L":
        case"M":
        case"N":
        case"R":
          n(f());
          break;
      }
      t+=o;
    }
    return r;
  
    function A(e){return e=b(e),e==="B"||e==="D"||e==="H"}
    function l(e){return e=b(e),e==="E"||e==="I"||e==="Y"}
    function p(e){return e=b(e),e==="A"||e==="E"||e==="I"||e==="O"||e==="U"}
    function K(e){return e=b(e),e==="C"||e==="G"||e==="P"||e==="S"||e==="T"}
    function v(e){let r=m(e); return r>=65&&r<=90}
    function m(e){return b(e).charCodeAt(0)}
    function b(e){return String(e).charAt(0).toUpperCase()}

  }

  //window['metaphone'] = H;
  String.prototype.metaphone = function(s){ return H( !!s ? s : this.valueOf() ); }
})();

// from metaphone project https://github.com/words/metaphone
