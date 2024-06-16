<!DOCTYPE html>
<html>
    <head>
        <style>
            body{
                font-family: Arial;
            }

            .header{
                display:flex;
                flex-direction: row;
                justify-content: space-between;
                height:200px;
                margin-left:280px;
                padding-top: 20px;
            }

            .left-section{
                display:flex;
                flex-direction: column;
            }

            .Cscar-sidebar{
                color:White;   
                display: flex;
                justify-content: center;
            }

            
            .sidebar{
                position:fixed;
                bottom:0px;
                left:0px;
                top: 0px;
                background-color: black ;
                width:250px;
              
            }

            .Cscar-sidebar{
                font-size:20px;
                margin-top:20px;
                
            }

            .options{
                display: flex;
                flex-direction: column;
                align-items:center;
                height:500px;
                color:white;
                background-color: black;
                margin-top:25px;w
            }

            .btn-sidebar{
                margin-top:30px;
            }

            .btn-design-sidebar{
                background-color: black;
                color:white;
                border:none;
                font-size:16px;
            }

            .help-support-btn{
                display:flex;
                justify-content: center;
                max-height:50px;
                margin-top:20px;
                

            }



        </style>
    </head>

    <body style="height: 1000px;">

        <div class="header">

            <div class="left-section">
                <div class="welcome-text">Welcome back, Matthew</div>
                <div class="general-function">Track, manage and forecast your clients, schedules, and maintenance</div>
            </div>
            <div class="right-section">
                <div><button>Bell</button></div>
                <div>
                <div>Escarlet Conde</div>
                <div>Admin</div>
                </div>
                <div>menu</div>
            </div>



        </div>
       
        <div class="sidebar">

            <div class="Cscar-sidebar"><p>CSCar</p></div>

            <div class="options">
                <div class="btn-sidebar"><button class="btn-design-sidebar">Dashboard</button></div>

                <div class="btn-sidebar"><button class="btn-design-sidebar">Fleet Management</button></div>

                <div class="btn-sidebar"><button class="btn-design-sidebar">Schedules</button></div>
                <div class="btn-sidebar"><button class="btn-design-sidebar">Drivers</button></div>
                <div class="btn-sidebar"><button class="btn-design-sidebar">Users</button></div>
                <div class="btn-sidebar"><button class="btn-design-sidebar">Settings</button></div>
            </div>

            <div class="help-support-btn"><button class="btn-design-sidebar">Help and Suppport</button></div>

           

        </div>



    </body>
</html>



