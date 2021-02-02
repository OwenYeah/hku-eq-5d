<?php
// ini_set('display_errors', true);
$host = 'localhost';
$dbname = 'limesurvey';
$username = 'limesurvey';
$password = 'vMZZrzeEfHdu9auc';

// Create connection
$db = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
} 

$surveyid = (int)$_GET['surveyid'];
$token = $_GET['token'];

$aData = [];
$aData['surveyid'] = $surveyid;
$aData['raw_data'] = [];

if(!empty($token)){

    // Population norms basic data
    $query = "SELECT attribute_1, attribute_2, attribute_3 FROM lime_tokens_".$surveyid." WHERE token = '".$token."'";
    $result = $db->query($query);
    $tokenData = mysqli_fetch_object($result);
    $result->close();

    if($tokenData->attribute_1 && $tokenData->attribute_2){
        $aData['sex'] = $tokenData->attribute_2;
        $aData['age'] = $tokenData->attribute_1;
        $aData['sno'] = $tokenData->attribute_3;
        if($tokenData->attribute_1 != 'N/A' && $tokenData->attribute_2 != 'N/A'){

            $ageGrp = [
                'A' => [18,24],
                'B' => [25,34],
                'C' => [35,44],
                'D' => [45,54],
                'E' => [55,64],
                'F' => [65,150]
            ];
            $norms = [
                        'Male' => [
                            'A' => [
                                'mean' => 0.936,
                                'lower' => 0.922,
                                'upper' => 0.950
                            ],
                            'B' => [
                                'mean' => 0.937,
                                'lower' => 0.919,
                                'upper' => 0.955
                            ],
                            'C' => [
                                'mean' => 0.943,
                                'lower' => 0.923,
                                'upper' => 0.963
                            ],
                            'D' => [
                                'mean' => 0.923,
                                'lower' => 0.898,
                                'upper' => 0.948
                            ],
                            'E' => [
                                'mean' => 0.901,
                                'lower' => 0.876,
                                'upper' => 0.926
                            ],
                            'F' => [
                                'mean' => 0.889,
                                'lower' => 0.844,
                                'upper' => 0.934
                            ],
                            'DEFAULT' => [
                                'mean' => 0.918,
                                'lower' => 0.908,
                                'upper' => 0.928
                            ]
                        ],
                        'Female' => [
                            'A' => [
                                'mean' => 0.941,
                                'lower' => 0.925,
                                'upper' => 0.957
                            ],
                            'B' => [
                                'mean' => 0.933,
                                'lower' => 0.913,
                                'upper' => 0.953
                            ],
                            'C' => [
                                'mean' => 0.942,
                                'lower' => 0.928,
                                'upper' => 0.956
                            ],
                            'D' => [
                                'mean' => 0.926,
                                'lower' => 0.906,
                                'upper' => 0.946
                            ],
                            'E' => [
                                'mean' => 0.890,
                                'lower' => 0.865,
                                'upper' => 0.915
                            ],
                            'F' => [
                                'mean' => 0.880,
                                'lower' => 0.841,
                                'upper' => 0.919
                            ],
                            'DEFAULT' => [
                                'mean' => 0.920,
                                'lower' => 0.908,
                                'upper' => 0.932
                            ]
                        ]
                    ];
                    $vas_norms = [
                        'A' => [
                            'mean' => 81,
                            'lower' => 60,
                            'upper' => 100
                        ],
                        'B' => [
                            'mean' => 82,
                            'lower' => 62,
                            'upper' => 100
                        ],
                        'C' => [
                            'mean' => 84,
                            'lower' => 63,
                            'upper' => 100
                        ],
                        'D' => [
                            'mean' => 85,
                            'lower' => 63,
                            'upper' => 100
                        ],
                        'E' => [
                            'mean' => 83,
                            'lower' => 57,
                            'upper' => 100
                        ],
                        'F' => [
                            'mean' => 83,
                            'lower' => 56,
                            'upper' => 100
                        ],
                        'DEFAULT' => [
                            'mean' => 83,
                            'lower' => 60,
                            'upper' => 100
                        ],
                    ];
                    $age = (int)$tokenData->attribute_1;
                    $marklines = $norms[$tokenData->attribute_2];
                    switch (true) {
                        case $age <= 24:
                            $aData['marklines'] = $marklines['A'];
                            $aData['vas_marklines'] = $vas_norms['A'];
                            # code...
                            break;

                        case $age <= 34:
                            $aData['marklines'] = $marklines['B'];
                            $aData['vas_marklines'] = $vas_norms['B'];
                            # code...
                            break;

                        case $age <= 44:
                            $aData['marklines'] = $marklines['C'];
                            $aData['vas_marklines'] = $vas_norms['C'];
                            # code...
                            break;

                        case $age <= 54:
                            $aData['marklines'] = $marklines['D'];
                            $aData['vas_marklines'] = $vas_norms['D'];
                            # code...
                            break;

                        case $age <= 64:
                            $aData['marklines'] = $marklines['E'];
                            $aData['vas_marklines'] = $vas_norms['E'];
                            # code...
                            break;

                        case $age <= 150:
                            $aData['marklines'] = $marklines['F'];
                            $aData['vas_marklines'] = $vas_norms['F'];
                            # code...
                            break;
                        
                        default:
                            # code...
                            $aData['marklines'] = $marklines['DEFAULT'];
                            $aData['vas_marklines'] = $vas_norms['DEFAULT'];
                            break;
                    }
        }
    }
    // End
    $query = "SELECT token, submitdate, 716222X1X1, 716222X1X2, 716222X1X3, 716222X1X4, 716222X1X5, 716222X2X6 FROM lime_survey_".$surveyid." WHERE token = '".$token."' AND submitdate IS NOT NULL ORDER BY submitdate DESC LIMIT 6";
    // $row = Yii::app()->db->createCommand($query)->queryAll();
    $result = $db->query($query);
    $row = [];
    while ($row[] = mysqli_fetch_array($result)) {
      # code...
    };
    $result->close();
    if(sizeof($row)){
        $summary=['716222X1X1', '716222X1X2', '716222X1X3', '716222X1X4', '716222X1X5'];
        $newRow = [];
        foreach ($row as $key => &$value) {
            # code...
            $sum = 0;
            $isAllEmtry = 0;
            foreach ($summary as $key2 => $value2) {
                list(, $qgid, $qqid) = explode("X", $value2, 3);
                if($value[$value2]){
                    $query = "SELECT assessment_value FROM lime_answers WHERE qid = ".$qqid." AND code = '".$value[$value2]."'";
                    $result = $db->query($query);
                    $row2 = mysqli_fetch_array($result);
                    $result->close();
                    $value[$value2.'-a'] = $row2['assessment_value'] / 1000.0;
                    $sum += $value[$value2.'-a'];
                } else {
                    $sum = -999;
                    $isAllEmtry++;
                }
            }
            if($isAllEmtry < 5) { // ignore the whole assessment if all answer is 'N/A'
                $value['healthstate'] = 1.0 - $sum;
                $newRow[] = $value;
            }
        }
        $aData['raw_data'] = [];
        for ($i=sizeof($newRow) - 1; $i >=0 ; $i--) { 
            $aData['raw_data'][] = $newRow[$i];
        }
        $aData['token'] = $token;
        $aData['summary'] = $summary;
        //Call the javascript file
        // App()->getClientScript()->registerScriptFile(App()->getConfig('adminscripts').'statistics.js');
        // App()->getClientScript()->registerScriptFile(App()->getConfig('adminscripts').'json-js/json2.min.js');
        // APP()->getClientScript()->registerScriptFile(App()->getConfig('generalscripts')."echarts.min.js");
    }
    extract($aData);
}

//Show Summary results
$aData['usegraph'] = 0;
$aData['output'] = "";
$aData['summary'] = $summary;
// $aData['oStatisticsHelper'] = $helper;
$aData['menu']['expertstats'] = false;
?>

<!DOCTYPE html>
<html>
  <head>
    <title>統計報告-<?=$sno?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
      <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
      <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
      <script src="/assets/scripts/echarts.min.js"></script>
      <script src="/third_party/jspdf/jspdf.min.js"></script>
      <script src="/third_party/jspdf/createpdf_worker.js"></script>
      <script src="/third_party/jszip/jszip.min.js"></script>
      <script src="/third_party/jszip/fileSaver.js"></script>
  </head>
  <style type="text/css">
    table{

    }
  </style>
  <body>
    <br/><br/>
    <div id='statisticsview-hku' class='side-body'>

      <div class="row">
          <?php if($token){ ?> 
          <div class="text-center">
              <!-- <a id="download" class="hide"><button class="button btn-warning btn-lg">Download</button></a> -->
              <!-- <a><button class="button btn-primary btn-lg">Preview</button></a> -->
          </div>
          <div class="col-sm-12" id="capture" style="background: white">
              <?php if(sizeof($raw_data)){?>
              <br/>
              <h4 class="text-center">Subject No. 參與者編號: <?php echo $sno; ?>&nbsp;&nbsp;&nbsp;<?php if($sex){?>Sex性別: <?php echo $sex ?> <?php } ?>&nbsp;&nbsp;&nbsp;<?php if($age){?>Age年齡: <?php echo $age ?><?php } ?></h4>
              <br/>
              <br/>
              <div class="table-responsive">
                  <table class="table table-bordered" style="width: auto; margin: 0 auto; font-size: 18px">
                      <thead>
                          <tr><td class="text-center" colspan=<?=sizeof($raw_data)+2?>>EQ-5D-5L Level: 1=no problem無問題, 2=slight problems有輕微問題, 3=moderate problems有中度問題, <br/>4=severe problems有嚴重問題, 5=extreme problems有極度問題</td></tr>
                          <tr>
                              <td></td>
                              <td width="60px">HK population median</td> <!--age-gender adjusted -->
                              <?php foreach ($raw_data as $key => $value) { ?>
                                  <td style="min-width: 60px"><?php echo date('Y', strtotime($value['submitdate'])) ?><br/><?php echo date('m-d', strtotime($value['submitdate'])) ?><br/><?php echo date('H:i', strtotime($value['submitdate'])) ?></td>
                              <?php } ?>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td class="text-bold">Mobility<br/>行動能力</td>
                              <td>1</td> <!--Todo-->
                              <?php foreach ($raw_data as $key => $value) { ?>
                                  <?php if(!empty($value[$summary[0]])){ ?> 
                                  <td><?php echo str_replace('A', '', $value[$summary[0]]); ?></td>
                                  <?php } else { ?> 
                                  <td>N/A</td>
                                  <?php } ?>
                              <?php } ?>
                          </tr>
                          <tr>
                              <td class="text-bold">Self-Care<br/>自我照顧</td>
                              <td>1</td> <!--Todo-->
                              <?php foreach ($raw_data as $key => $value) { ?>
                                  <?php if(!empty($value[$summary[1]])){ ?> 
                                  <td><?php echo str_replace('A', '', $value[$summary[1]]); ?></td>
                                  <?php } else { ?> 
                                  <td>N/A</td>
                                  <?php } ?>
                              <?php } ?>
                          </tr>
                          <tr>
                              <td class="text-bold">Usual activities<br/>平常活動</td>
                              <td>1</td> <!--Todo-->
                              <?php foreach ($raw_data as $key => $value) { ?>
                                  <?php if(!empty($value[$summary[2]])){ ?> 
                                  <td><?php echo str_replace('A', '', $value[$summary[2]]); ?></td>
                                  <?php } else { ?> 
                                  <td>N/A</td>
                                  <?php } ?>
                              <?php } ?>
                          </tr>
                          <tr>
                              <td class="text-bold">Pain/discomfort<br/>疼痛/不舒服</td>
                              <td>1</td> <!--Todo-->
                              <?php foreach ($raw_data as $key => $value) { ?>
                                  <?php if(!empty($value[$summary[3]])){ ?> 
                                  <td><?php echo str_replace('A', '', $value[$summary[3]]); ?></td>
                                  <?php } else { ?> 
                                  <td>N/A</td>
                                  <?php } ?>
                              <?php } ?>
                          </tr>
                          <tr>
                              <td class="text-bold">Anxiety/depression<br/>焦慮/沮喪</td>
                              <td>2</td> <!--Todo-->
                              <?php foreach ($raw_data as $key => $value) { ?>
                                  <?php if(!empty($value[$summary[4]])){ ?> 
                                  <td><?php echo str_replace('A', '', $value[$summary[4]]); ?></td>
                                  <?php } else { ?> 
                                  <td>N/A</td>
                                  <?php } ?>
                              <?php } ?>
                          </tr>
                      </tbody>
                  </table>
              </div>
                  

                <br/>
                <br/>
                <br/>
                <div style=" position: relative;">
                    <div id="main" style="width: 100%;height:500px; margin: 0 auto; max-width: 900px;"></div>
                    <div id="main-line" style="display: none; position: absolute; top: 110px; left: -100px; font-size: 18px">Mean score by <br/>age group and sex <br/>按年齡組別及<br/>性別之平均值</div>
                </div>
                <br/>
                <br/>
                <br/>
                <div style=" position: relative;">
                    <div id="main2" style="width: 100%;height:400px; margin: 0 auto; max-width: 900px"></div>
                    <div id="main2-line" style="display: none; position: absolute; top: 110px; left: -80px; font-size: 18px">Mean value by <br/>age group<br/>按年齡組別<br/>之平均分</div>
                </div>


                <script type="text/javascript">
                $(document).on('ready pjax:complete',function() {
                    var myChart = echarts.init(document.getElementById('main'));
                    var x = [], series = [];
                    <?php foreach($raw_data AS $key=>$value){ ?>
                        <?php if($value['healthstate'] <= 1){?>
                        x.push( "<?php echo date('Y-m-d', strtotime($value['submitdate'])); ?>\n<?php echo date('H:i', strtotime($value['submitdate'])) ?>" );
                        // x.push( "<?php echo 'Time'.($key+1) ?>" );
                        series.push( {value: "<?php echo (float)$value['healthstate'] ?>", label: {show: true} } );
                        <?php } ?>
                    <?php } ?>

                    // 指定图表的配置项和数据
                    var option = {
                        title: {
                            text: 'EQ-5D-5L Utility Score (綜合健康值)',
                            left: 'center'
                        },
                        tooltip : {
                            trigger: 'item',
                            axisPointer: {
                                type: 'cross',
                                label: {
                                    backgroundColor: '#6a7985'
                                }
                            }
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: true,
                            data: x,
                            axisLabel:{
                                textStyle:{
                                    fontSize: '16',
                                    fontStyle: 'italic'
                                },
                                interval:0,//0：全部显示，1：间隔为1显示对应类目，2：依次类推，（简单试一下就明白了，这样说是不是有点抽象）
                                rotate: 30,//倾斜显示，-：顺时针旋转，+或不写：逆时针旋转
                            },
                            axisLine:{
                                show: false
                            },
                            axisPointer: {
                                show: false
                            },
                            slient: true
                        },
                        yAxis: {
                            type: 'value',
                            boundaryGap: [-1.2, 1.2],
                            min: -1,
                            max: 1,
                            minInterval: 0.2,
                            maxInterval: 0.2,
                            axisLabel:{
                                textStyle:{
                                    fontSize: '24'
                                }
                            },
                            splitLine:{
                        　　　　show:false
                            },
                            axisPointer: {
                                show: false
                            },
                            slient: true
                        },
                        series: [
                            {
                                data: series,
                                type: "line",
                                label: {
                                    fontSize: 18
                                }
                            },
                            // {
                            //     name:"HK Population Median",
                            //     type:"line",
                            //     data:[{
                            //         value: 0.521
                            //     }],
                            //     markLine: {
                            //         lineStyle: {
                            //             type: 'solid'
                            //         },
                            //         precision: 3,
                            //         data: [
                            //             {type: "average", name: "value"}
                            //         ]
                            //     }
                            // }
                            // {
                            //     name:"HK Population Mean",
                            //     type:"line",
                            //     data:[{
                            //         value: 0.87
                            //     }],
                            //     markLine: {
                            //         lineStyle: {
                            //             type: 'solid'
                            //         },
                            //         precision: 3,
                            //         data: [
                            //             {type: "average", name: "value"}
                            //         ]
                            //     }
                            // }
                            <?php if(isset($sex)){ ?> 
                            ,{
                                name:"HK Population Mean",
                                type:"line",
                                data:[<?php echo $marklines['mean'] ?>],
                                markLine: {
                                    lineStyle: {
                                        type: 'solid'
                                    },
                                    precision: 3,
                                    data: [
                                        {type: "average", name: "<?php echo $sex ?>"}
                                    ],
                                    label: {
                                        fontSize: 24
                                    }
                                }
                            }
                            // {
                            //     name:"95.0% Lower CL for Mean",
                            //     type:"line",
                            //     data:[<?php echo $marklines['lower'] ?>],
                            //     markLine: {
                            //         precision: 3,
                            //         data: [
                            //             {type: "average", name: "<?php echo $sex ?>"}
                            //         ]
                            //     }
                            // },
                            // {
                            //     name:"95.0% Upper CL for Mean",
                            //     type:"line",
                            //     data:[<?php echo $marklines['upper'] ?>],
                            //     markLine: {
                            //         precision: 3,
                            //         data: [
                            //             {type: "average", name: "<?php echo $sex ?>"}
                            //         ]
                            //     }
                            // }
                            <?php } ?>
                            
                        ]
                    };
                    myChart.setOption(option);

                    
                    var myChart = echarts.init(document.getElementById('main2'));
                    var x = [], series = [];
                    <?php foreach($raw_data AS $key=>$value){ ?>
                        <?php if($value['716222X2X6'] != ''){?>
                        x.push( "<?php echo date('Y-m-d', strtotime($value['submitdate'])); ?>\n<?php echo date('H:i', strtotime($value['submitdate'])) ?>" );
                        series.push( {value: "<?php echo (float)$value['716222X2X6'] ?>", label: {show: true} } )
                        <?php } ?>
                    <?php } ?>

                    // 指定图表的配置项和数据
                    var option = {
                        title: {
                            text: 'EQ-5D-5L VAS (自我健康評分)​',
                            left: 'center'
                        },
                        tooltip : {
                            trigger: 'item',
                            axisPointer: {
                                type: 'cross',
                                label: {
                                    backgroundColor: '#6a7985'
                                }
                            }
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: true,
                            data: x,
                            axisLabel:{
                                textStyle:{
                                    fontSize: '16',
                                    fontStyle: 'italic'
                                },
                                interval:0,//0：全部显示，1：间隔为1显示对应类目，2：依次类推，（简单试一下就明白了，这样说是不是有点抽象）
                                rotate: 30,//倾斜显示，-：顺时针旋转，+或不写：逆时针旋转
                            },
                            axisPointer: {
                                show: false
                            },
                            slient: true
                        },
                        yAxis: {
                            type: 'value',
                            boundaryGap: [0, 1.2],
                            min: 0,
                            max: 100,
                            data: [0, 20, 40, 60, 80, 100],
                            axisLabel:{
                                textStyle:{
                                    fontSize: '24'
                                }
                            },
                            axisPointer: {
                                show: false
                            },
                            slient: true
                        },
                        series: [
                            {
                                data: series,
                                type: "line",
                                label: {
                                    fontSize: 24
                                },
                                animation:false
                            }
                            <?php if(isset($sex)){ ?> 
                            ,{
                                name:"HK Population Mean",
                                type:"line",
                                data:[<?php echo $vas_marklines['mean'] ?>],
                                markLine: {
                                    lineStyle: {
                                        type: 'solid'
                                    },
                                    precision: 3,
                                    data: [
                                        {type: "average", name: "<?php echo $sex ?>"}
                                    ],
                                    label: {
                                        fontSize: 24
                                    }
                                }
                            }
                            <?php } ?>
                        ]
                    };
                    myChart.setOption(option);

                    var downloadName = '<?php echo $token ?>.png';
                    var meanLineValue = parseFloat(<?php echo $marklines['mean'] ?>);
                    // var meanLineValue = 0.87;

                    $("#main-line").css("display", "block");
                    $("#main-line").css("top", 80 + 90*(1-(meanLineValue - 0.521)/0.32));
                    $("#main").append($("#main-line"));

                    var meanLineValue2 = parseFloat(<?php echo $vas_marklines['mean'] ?>);
                    // var meanLineValue = 0.87;

                    $("#main2-line").css("display", "block");
                    $("#main2-line").css("top", meanLineValue2);
                    $("#main2").append($("#main2-line"));


                    document.getElementById('download').addEventListener('click', function() {
                            // $("#main-line").css("display", "block");
                            // $("#main-line").css("top", 110 + 90*(1-(meanLineValue - 0.521)/0.32));
                            // $("#main").append($("#main-line"));
                            downloadCanvas(this, downloadName);
                            $(this).removeClass("hide");
                      }, false);
                    setTimeout(function(){
                        document.getElementById('download').click();
                        $(this).removeClass("hide");
                    }, 1000);

                      function downloadCanvas(link, filename) {

                        // $("#main-line").css("display", "block");
                        // var temp = $("#main").offset().top;
                        // $("#main-line").css("top", temp-250);
                        // var temp = $("#main").offset().left + $("#main").width() - 60;
                        // $("#main-line").css("left", temp);

                        var myDom = document.querySelector("#capture");
                        // myDom.style.transform = myDom.style.webkitTransform = 'scale(2)';
                        // myDom.style.transformOrigin = myDom.style.webkitTransformOrigin = '0 0';
                        html2canvas(myDom).then(canvas => {
                          var image = canvas.toDataURL({
                            format: 'png',
                            quality: 1,
                            // width: myDom.offsetWidth * 2,
                            // height: myDom.offsetHeight * 2
                          });

                            // $("#main-line").css("display", "none");
                          // .replace("image/png", "image/octet-stream");
                          // window.location.href = image; // it will save locally

                          link.href = image;
                          link.download = filename;
                        });
                      }
                });
            </script>
            <?php } else { ?> 
                <p class="text-center"><?php eT("Record does not exist, please try another token"); ?></p>
            <?php }?>
            

            <?php } ?>
        </div>
    </div>
</div>
</html>