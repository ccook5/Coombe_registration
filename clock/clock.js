function startTime()
{
    var today=new Date();
    var h=today.getHours();
    var m=today.getMinutes();
    var s=today.getSeconds();
    var weekday=new Array(7);
        weekday[0]="Sunday";
        weekday[1]="Monday";
        weekday[2]="Tuesday";
        weekday[3]="Wednesday";
        weekday[4]="Thursday";
        weekday[5]="Friday";
        weekday[6]="Saturday";
    var day=weekday[today.getDay()];
    var d=today.getDate();
    var month=new Array(12);
        month[0]="January";
        month[1]="February";
        month[2]="March";
        month[3]="April";
        month[4]="May";
        month[5]="June";
        month[6]="July";
        month[7]="August";
        month[8]="September";
        month[9]="October";
        month[10]="November";
        month[11]="December";
    var mon=month[today.getMonth()];
    var y=today.getFullYear();
    
    // add a zero in front of numbers<10
    m=checkTime(m);
    s=checkTime(s);
    document.getElementById('time').innerHTML=h+":"+m+":"+s;
    document.getElementById('date').innerHTML=day+" "+d+" "+mon+" "+y;
    t=setTimeout(function(){startTime()},500);
}

function checkTime(i)
{
    if (i<10)
    {
        i="0" + i;
    }
    return i;
}


function startPage()
{
    startTime();
}

