
function str_prepare(str) {
  let a = str.split(' ').filter( (e)=>{return e.length>2} );
  return a.length ? a.join(' ') : false;
}



function search(search_str) {
  let index = [];
  for (let word of search_str.split(' ')) {
     
    let w = translit(word).metaphone();
    let dir = './i/'+w.substring(0,2)+'/';
    console.log(word,dir,w);

    //$(document).on('ajaxBeforeSend', function(e, xhr, options){
      // This gets fired for every Ajax request performed on the page.
      // The xhr object and $.ajax() options are available for editing.
      // Return false to cancel this request.
    //})
    
    //$.getJSON('./index.json',
      //url: './index.json',
    $.ajax({
      type: 'GET',
      url: dir+w+'.json',
      async: false,   //  NB
      dataType: 'json',
      timeout: 3000,  //  ms
      success: function(data, status, xhr){
        console.log(word+' getJSON done');
        console.log(data);
        
        data.word = word;
        index[index.length] = data;
      },
      error: function(xhr, type, status){
        console.log(word,'dont load:', type, status);
        index[index.length] = {'word':word,i:[],w:''};
      }
    });
  }
  console.log('index',index);
  //let res = _.map( index, function(v,k){ return v['i']; });
  //let result = _.intersection( _.map( index, function(v,k){ return v['i']; }) );
  //console.log('result',result,res);
  
  if (!!index) {
    $div = $('.show-result');
    $listmark = $('.words', $div);
    
    if (index.length>1) {
      _.map( index, function(v,k){
        //console.log('insert ',v['word']);
        $listmark.append('<li class="list-item">Найдено <i>'+v['word']+'</i>: '+v['i'].length+'</li>'); 
        //return v['i']; 
      });
    }
    
    res_ids = _.reduce( _.map(index,(e)=>{return e.i})
      , function(a,b){return _.intersection(a, b);} );
    $listmark.append('<li class="list-item">Всего результатов "<b>'+search_str+'</b>": '+res_ids.length+'</li>');

    res_ids = _.shuffle(res_ids).slice(0,12);       
    result = []
    for (let id of res_ids) {
    
      let dir = './i/'+String(id).substring(0,3)+'/';
      console.log(dir,id);
    
      $.ajax({
        type: 'GET',
        url: dir+id+'.json',
        async: false,   //  NB
        dataType: 'json',
        timeout: 3000,  //  ms
        success: function(data, status, xhr){
          console.log('ID '+id+' getJSON done');
          //console.log(data);
          
          result[result.length] = data;
        },
        error: function(xhr, type, status){
          console.log(id,'dont load:', type, status);
          //result[result.length] = {'word':word,i:[],w:''};
        }
      });
    }
    
    //result = _.shuffle(result).slice(0,12);
    console.log(result);
    
    $booklist = $('.books', $div);
    _.map( result, function(v,k){
      //console.log('insert ',v['word']);
      $booklist.append('<li class="list-item"><small>'
        +'<a target=_blank href="//flib.flibusta.is/b/'+v.i+'">ID '+v.i+'</a> '
        +'<a target=_blank href="//flib.flibusta.is/b/'+v.i+'/fb2">fb2</a> '
        +'<a target=_blank href="//flib.flibusta.is/b/'+v.i+'/epub">epub</a></small> '
        +'<b>'+v.t+'</b> <i>(<small>'+v.n+'</small>)</i></li>'
      ); 
    });

    
  }
}


function translit(text, tr_table) {
//  informal transliteration

  let $table_rus_translit_gost = {
  "а":"a","б":"b","в":"v","г":"g","д":"d",
  "е":"e","ё":"yo","ж":"j","з":"z","и":"i",
  "й":"i","к":"k","л":"l", "м":"m","н":"n",
  "о":"o","п":"p","р":"r","с":"s","т":"t",
  "у":"y","ф":"f","х":"h","ц":"c","ч":"ch",
  "ш":"sh","щ":"sh","ы":"i","э":"e","ю":"u","я":"ya",
    
  "А":"A","Б":"B","В":"V","Г":"G","Д":"D",
  "Е":"E","Ё":"Yo","Ж":"J","З":"Z","И":"I",
  "Й":"I","К":"K","Л":"L","М":"M","Н":"N",
  "О":"O","П":"P","Р":"R","С":"S","Т":"T",
  "У":"Y","Ф":"F","Х":"H","Ц":"C","Ч":"Ch",
  "Ш":"Sh","Щ":"Sh","Ы":"I","Э":"E","Ю":"U","Я":"Ya",
    
  "ь":"","Ь":"","ъ":"","Ъ":"",
  "ї":"j","і":"i","ґ":"g","є":"ye",
  "Ї":"J","І":"I","Ґ":"G","Є":"YE"
  };
  
  let $table_translit_rus_gost = {
  "a":"а","b":"б","v":"в","g":"г","d":"д",
  "e":"е","yo":"ё","j":"ж","z":"з","i":"и",
  "i":"й","k":"к","l":"л","m":"м","n":"н",
  "o":"о","p":"п","r":"р","s":"с","t":"т",
  "y":"у","f":"ф","h":"х","c":"ц","ch":"ч",
  "sh":"ш","sh":"щ","i":"ы","e":"е","u":"у","ya":"я",
    
  "A":"А","B":"Б","V":"В","G":"Г","D":"Д",
  "E":"Е","Yo":"Ё","J":"Ж","Z":"З","I":"И",
  "I":"Й","K":"К","L":"Л","M":"М","N":"Н",
  "O":"О","P":"П","R":"Р","S":"С","T":"Т",
  "Y":"Ю","F":"Ф","H":"Х","C":"Ц","Ch":"Ч",
  "Sh":"Ш","Sh":"Щ","I":"Ы","E":"Е","U":"У","Ya":"Я",
    
  "'":"ь","'":"Ь","''":"ъ","''":"Ъ",
  "j":"ї","i":"и","g":"ґ","ye":"є",
  "J":"Ї","I":"І","G":"Ґ","YE":"Є"
  };
  
  
  let $table_cyr_lat = {
  "а":"a","б":"b","в":"v","г":"g","д":"d",
  "е":"e", "ё":"jo","ж":"zh","з":"s","и":"i",
  "й":"j","к":"k","л":"l", "м":"m","н":"n",
  "о":"o","п":"p","р":"r","с":"s","т":"t",
  "у":"u","ф":"f","х":"kh","ц":"ts","ч":"ch",
  "ш":"sh","щ":"sch","ы":"y","э":"ae","ю":"ju", "я":"ja",
    
  "А":"A","Б":"B","В":"V","Г":"G","Д":"D",
  "Е":"E","Ё":"Jo","Ж":"Zh","З":"S","И":"I",
  "Й":"J","К":"K","Л":"L","М":"M","Н":"N",
  "О":"O","П":"P","Р":"R","С":"S","Т":"T",
  "У":"U","Ф":"F","Х":"Kh","Ц":"Ts","Ч":"Ch",
  "Ш":"Sh","Щ":"Sch","Ы":"Y","Э":"Ae","Ю":"Ju","Я":"Ja",
    
  "ь":"","Ь":"","ъ":"","Ъ":"",
  "ї":"j","і":"i","ґ":"g","є":"ye",
  "Ї":"J","І":"I","Ґ":"G","Є":"YE"
  };
  
  let $table_lat_cyr = {
  "a":"а","b":"б","v":"в","g":"г","d":"д","e":"е","yo":"ё","jo":"ё",
  "zh":"ж","z":"з","s":"з","i":"и","j":"й","k":"к",
  "l":"л","m":"м","n":"н","o":"о","p":"п","r":"р","s":"с","t":"т",
  "y":"ы","f":"ф","h":"х","c":"ц",
  "ch":"ч","sh":"ш","sh":"щ","i":"ы","e":"е","u":"у","ya":"я","A":"А","B":"Б",
  "V":"В","G":"Г","D":"Д", "E":"Е","Yo":"Ё","J":"Ж","Z":"З","I":"И","I":"Й","K":"К","L":"Л","M":"М",
  "N":"Н","O":"О","P":"П",
  "R":"Р","S":"С","T":"Т","Y":"Ю","F":"Ф","H":"Х","C":"Ц","Ch":"Ч","Sh":"Ш",
  "Sh":"Щ","I":"Ы","E":"Е", "U":"У","Ya":"Я","'":"ь","'":"Ь","''":"ъ","''":"Ъ","j":"ї","i":"и","g":"ґ",
  "ye":"є","J":"Ї","I":"І",
  "G":"Ґ","YE":"Є"
  };
  
  
  if (!tr_table) {
		tr_table = $table_cyr_lat;
  }
  
  return text.replace(/./gm, (l)=>{ return (tr_table.hasOwnProperty(l)) ? tr_table[l] : l ; });
}
