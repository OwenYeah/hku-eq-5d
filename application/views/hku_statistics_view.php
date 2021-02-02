<!-- owen -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<style type="text/css">
      /* Page Breaks */

/***Always insert a page break before the element***/
       .pb_before {
           page-break-before: always !important;
       }

/***Always insert a page break after the element***/
       .pb_after {
           page-break-after: always !important;
       }

/***Avoid page break before the element (if possible)***/
       .pb_before_avoid {
           page-break-before: avoid !important;
       }

/***Avoid page break after the element (if possible)***/
       .pb_after_avoid {
           page-break-after: avoid !important;
       }

/* Avoid page break inside the element (if possible) */
       .pbi_avoid {
           page-break-inside: avoid !important;
       }

       body {
         overflow:hidden;
       }

</style>
<div id='statisticsview-hku' class='side-body'>

    <div class="row">
        <?php if($token){?> 
        <div class="col-sm-12" id="capture" style="background: white">
            <?php if(sizeof($raw_data)){?>
            <br/>
            <h4 class="text-center">Subject No. 參與者編號: <?php echo $sno; ?>&nbsp;&nbsp;&nbsp;<?php if($sex){?>Sex性別: <?php echo $sex ?> <?php } ?>&nbsp;&nbsp;&nbsp;<?php if($age){?>Age年齡: <?php echo $age ?><?php } ?></h4>
            <br/>
            <br/>
            <div class="table-responsive">
                <table class="table table-bordered" style="width: 95%; margin: 0 auto; font-size: 18px">
                    <thead>
                        <tr><td class="text-center" colspan=<?=sizeof($raw_data)+2?>>EQ-5D-5L Level: 1=no problem無問題, 2=slight problems有輕微問題,  <br/>3=moderate problems有中度問題, 4=severe problems有嚴重問題, 5=extreme problems有極度問題</td></tr>
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
                    <div id="main" style="width: 960px;height:500px; margin: 0 auto; max-width: 900px;"></div>
                    <div id="main-line" style="display: none; position: absolute; top: 110px; left: -120px; font-size: 18px">Mean score by <br/>age group and sex <br/>按年齡組別及<br/>性別之平均值</div>
                </div>
                <br/>
                <br/>
                <br/>
                <div style=" position: relative;">
                    <div id="main2" style="width: 960px;height:400px; margin: 0 auto; max-width: 900px"></div>
                    <div id="main2-line" style="display: none; position: absolute; top: 110px; left: -80px; font-size: 18px">Mean value by <br/>age group<br/>按年齡組別<br/>之平均分</div>
                </div>
                


                <script type="text/javascript">
                setTimeout(function(){
                    window.status = "ready_to_print";
                }, 5000);

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
                            left: 'center',
                            textStyle: {
                                fontSize: 24
                            }
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
                                    fontSize: 24
                                },
                                animation:false
                            },
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
                            //     },
                            //     animation:false
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
                            left: 'center',
                            textStyle: {
                                fontSize: 24
                            }
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
                            },
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
                    $("#main2-line").css("display", "block");
                    $("#main2-line").css("top", meanLineValue2);
                    $("#main2").append($("#main2-line"));

                // $(document).on('ready pjax:complete',function() {
                    
                // });
            </script>
            <?php } else { ?> 
                <p class="text-center"><?php eT("Record does not exist, please try another token"); ?></p>
            <?php }?>
            

            <?php } ?>
        </div>
    </div>
</div>