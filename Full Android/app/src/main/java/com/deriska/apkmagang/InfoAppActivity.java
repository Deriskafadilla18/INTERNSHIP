package com.deriska.apkmagang;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;

public class InfoAppActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_info_app);
        getSupportActionBar().setElevation(0);
    }
}
